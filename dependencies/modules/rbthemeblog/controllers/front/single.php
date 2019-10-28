<?php
/**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

require_once _PS_MODULE_DIR_.'rbthemeblog/rbthemeblog.php';

use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;

class RbthemeblogSingleModuleFrontController extends ModuleFrontController
{
	protected $rbBlogPost;

	public function init()
    {
        parent::init();

        $rbblog_post_rewrite = Tools::getValue('rewrite');

        if ($rbblog_post_rewrite &&
        	Validate::isLinkRewrite($rbblog_post_rewrite)
        ) {
            $this->rbblog_post_rewrite = $rbblog_post_rewrite;
        } else {
            die('Blog for PrestaShop: URL is not valid');
        }

        $rbBlogPost = RbBlogPost::getByRewrite(
        	$this->rbblog_post_rewrite,
        	(int) Context::getContext()->language->id
        );

        // Check for matching current url with post url informations
        if (!Validate::isLoadedObject($rbBlogPost) ||
        	Validate::isLoadedObject($rbBlogPost) &&
        	!$rbBlogPost->active
        ) {
            Tools::redirect('index.php?controller=404');
        }

        if (Validate::isLoadedObject($rbBlogPost) &&
        	$this->rbblog_post_rewrite != $rbBlogPost->link_rewrite ||
            Tools::getValue('rb_category') != $rbBlogPost->category_rewrite
        ) {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: '.RbBlogPost::getLink(
            	$rbBlogPost->link_rewrite,
            	$rbBlogPost->category_rewrite
            ));
        }

        // There you go, our blog post
        $this->rbBlogPost = $rbBlogPost;

        // Check access to post
        if (!$this->rbBlogPost->isAccessGranted()) {
            Tools::redirect('index.php?controller=404');
        }

        // Assign meta tags
        $this->assignMetas();
    }

    public function checkForSmartShortcodeAddons()
    {
        $context = Context::getContext();
        $dir = _PS_MODULE_DIR_.'smartshortcode/addons';

        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != '.' && $file != '..') {
                        if (is_dir("{$dir}/{$file}/front")) {
                            include_once "{$dir}/{$file}/front/shortcode.php";
                        }
                    }
                }
                closedir($dh);
            }
        }
    }


    public function initContent()
    {
        $this->addModulePageAssets();

        parent::initContent();

        $this->rbBlogPost->increaseViewsNb();
        $this->supportThirdPartyPlugins();
        $this->context->smarty->assign('post', $this->rbBlogPost);
        $this->context->smarty->assign('guest', (int) $this->context->cookie->id_guest);
        $this->context->smarty->assign('gallery_dir', _MODULE_DIR_.'rbthemeblog/galleries/');

        Media::addJsDef(
            array(
                'rbthemeblog_ajax'    => $this->context->link->getModuleLink('rbthemeblog', 'ajax'),
                'rbthemeblog_token'   => $this->module->secure_key,
            )
        );

        // Comments
        $this->prepareCommentsSection();

        // Related products
        if (Configuration::get('RBTHEMEBLOG_BLOG_DISPLAY_RELATED')) {
            $related_products = $this->getRelatedProducts();
            $this->context->smarty->assign('related_products', $related_products);
        }

        $this->setTemplate('module:rbthemeblog/views/templates/front/single.tpl');
    }

    public function getRelatedProducts()
    {
        $products = RbBlogPost::getRelatedProducts($this->rbBlogPost->id_product);
        $productsArray = array();

        if ($products) {
            $assembler = new ProductAssembler($this->context);

            $presenterFactory = new ProductPresenterFactory($this->context);
            $presentationSettings = $presenterFactory->getPresentationSettings();
            $presenter = new ProductListingPresenter(
                new ImageRetriever(
                    $this->context->link
                ),
                $this->context->link,
                new PriceFormatter(),
                new ProductColorsRetriever(),
                $this->context->getTranslator()
            );

            foreach ($products as $rawProduct) {
                $productInfo = $assembler->assembleProduct($rawProduct);
                if ($productInfo) {
                    $productsArray[] = $presenter->present(
                        $presentationSettings,
                        $productInfo,
                        $this->context->language
                    );
                }
            }
        }

        return $productsArray;
    }

    public function getBreadcrumbLinks()
    {
        $breadcrumb = parent::getBreadcrumbLinks();

        $id_lang = Context::getContext()->language->id;

        $rbBlogPost = RbBlogPost::getByRewrite(
        	$this->rbblog_post_rewrite,
        	$id_lang
        );

        $breadcrumb['links'][] = array(
            'title' => $this->l('Blog'),
            'url' => Rbthemeblog::getLink()
        );

        $breadcrumb['links'][] = array(
            'title' => $rbBlogPost->category,
            'url' => $rbBlogPost->category_url
        );

        $breadcrumb['links'][] = array(
            'title' => $rbBlogPost->title,
            'url' => $rbBlogPost->url
        );

        return $breadcrumb;
    }

    public function postProcess()
    {
        $errors = array();
        $confirmation = '1';

        if (Tools::isSubmit('submitNewComment') &&
        	Tools::getValue('id_rbblog_post')
        ) {
            if (Configuration::get('RBTHEMEBLOG_BLOG_COMMENT_ALLOW_GUEST') && Configuration::get('RBTHEMEBLOG_BLOG_COMMENTS_RECAPTCHA')
        	) {
                if (!class_exists('ReCaptcha') &&
                	file_exists(_PS_MODULE_DIR_ . 'rbthemeblog/library/recaptchalib.php')
                ) {
                    require_once(_PS_MODULE_DIR_ . 'rbthemeblog/library/recaptchalib.php');
                }

                $secret = Configuration::get(
                	'RBTHEMEBLOG_BLOG_COMMENTS_RECAPTCHA_SECRET_KEY'
                );

                $response = null;

                if (!class_exists('ReCaptcha')) {
                    die('Sorry, you want to use reCAPTCHA but class to handle this doesn\'t exists');
                }

                $reCaptcha = new ReCaptcha($secret);

                if (Tools::getValue('g-recaptcha-response')) {
                    $response = $reCaptcha->verifyResponse(
                        $_SERVER["REMOTE_ADDR"],
                        Tools::getValue('g-recaptcha-response')
                    );
                }

                if ($response == null) {
                    $errors[] = $this->module->l(
                    	'Please provide valid reCAPTCHA value'
                    );
                }
            }

            if (Tools::getValue('comment_content') &&
            	Validate::isGenericName(Tools::getValue('comment_content'))
            ) {
                $comment_content = Tools::getValue('comment_content');
            } else {
                $errors[] = $this->module->l(
                	'Please write something and remember that HTML is not allowed in comment content.'
                );
            }

            $customer_name = Tools::getValue('customer_name');

            if (!Validate::isGenericName($customer_name)) {
                $errors[] = $this->module->l(
                	'Please provide valid name'
                );
            }

            if (!Validate::isInt(Tools::getValue('id_parent'))) {
                die('FATAL ERROR [2]');
            } else {
                $id_parent = Tools::getValue('id_parent');
            }

            $ip_address = Tools::getRemoteAddr();

            if (sizeof($errors)) {
                $this->errors = $errors;
            } else {
                $comment = new RbBlogComment();
                $comment->id_rbblog_post = (int)Tools::getValue('id_rbblog_post');
                $comment->id_parent = $id_parent;
                $comment->id_customer = (int)$this->context->customer->id ? (int)$this->context->customer->id : 0;
                $comment->id_guest = (int)$this->context->customer->id_guest ? (int)$this->context->customer->id_guest : 0;
                $comment->name = $customer_name;
                $comment->email = isset($this->context->customer->email) ? $this->context->customer->email : null;
                $comment->comment = $comment_content;
                $comment->active = Configuration::get('RBTHEMEBLOG_BLOG_COMMENT_AUTO_APPROVAL') ? 1 : 0;
                $comment->ip = Tools::getRemoteAddr();
                
                if ($comment->add()) {
                    if (!Configuration::get('RBTHEMEBLOG_BLOG_COMMENT_AUTO_APPROVAL')
                	) {
                        $confirmation = $this->l(
                        	'Your comment was sucessfully added but it will be visible after moderator approval.'
                        );
                    } else {
                        $confirmation = $this->l(
                        	'Your comment was sucessfully added.'
                        );
                    }

                    $link = $this->context->link->getModuleLink(
                    	'rbthemeblog',
                    	'single',
                    	array(
                    		'rewrite' => $this->rbBlogPost->link_rewrite,
                    		'rb_category' => $this->rbBlogPost->category_rewrite
                    	)
                    );

                    if (Configuration::get('RBTHEMEBLOG_BLOG_COMMENT_NOTIFICATIONS')
                	) {
                        $toName = strval(Configuration::get('PS_SHOP_NAME'));
                        $fromName = strval(Configuration::get('PS_SHOP_NAME'));
                        $to = Configuration::get('PH_BLOG_COMMENT_NOTIFY_EMAIL');
                        $from = Configuration::get('PS_SHOP_EMAIL');
                        $customer = $this->context->customer;

                        if ($this->context->customer->isLogged()) {
                            $lastname = $this->context->customer->lastname;
                            $firstname = $this->context->customer->firstname;
                        } else {
                            $lastname = '';
                            $firstname = $customer_name;
                        }

                        Mail::Send(
                            $this->context->language->id,
                            'new_comment',
                            sprintf(
                            	$this->l(
                            		'New comment on %1$s blog for article: %2$s'
                            	),
                            	$toName,
                            	$this->rbBlogPost->title
                            ),
                            array(
                                '{lastname}' => $customer->lastname,
                                '{firstname}' => $customer->firstname,
                                '{post_title}' => $this->rbBlogPost->title,
                                '{post_link}' => $this->rbBlogPost->url,
                                '{comment_content}' => $comment_content,
                            ),
                            $to,
                            $toName,
                            $from,
                            $fromName,
                            null,
                            null,
                            _PS_MODULE_DIR_.'rbthemeblog/mails/'
                        );
                    }

                    $this->success[] = $confirmation;

                    $this->redirectWithNotifications($link);
                } else {
                    $this->errors = $this->module->l(
                    	'Something went wrong! Comment can not be added!'
                    );
                }
            }
        }
        return parent::postProcess();
    }

    /*** Assign meta tags to single post page. */
    protected function assignMetas()
    {
        if (!empty($this->rbBlogPost->meta_title)) {
            $this->context->smarty->assign(
            	'meta_title',
            	$this->rbBlogPost->meta_title
            );
        } else {
            $this->context->smarty->assign(
            	'meta_title',
            	$this->rbBlogPost->title
            );
        }

        if (!empty($this->rbBlogPost->meta_description)) {
            $this->context->smarty->assign(
            	'meta_description',
            	$this->rbBlogPost->meta_description
            );
        }

        if (!empty($this->rbBlogPost->meta_keywords)) {
            $this->context->smarty->assign(
            	'meta_keywords',
            	$this->rbBlogPost->meta_keywords
            );
        }
    }

    /**
     * Prepare comments section, check for access to add comments etc.
     */
    protected function prepareCommentsSection()
    {
        $this->context->smarty->assign(
        	'allow_comments',
        	$this->rbBlogPost->allowComments()
        );

        if ($this->rbBlogPost->allowComments() == true) {
            $comments = RbBlogComment::getComments(
            	$this->rbBlogPost->id_rbblog_post
            );

            $this->context->smarty->assign('comments', $comments);
        }
    }

    /**
     * CSS, JS and other assets for this page.
     */
    protected function addModulePageAssets()
    {
        $this->context->controller->addJqueryPlugin('cooki-plugin');
        $this->context->controller->addJqueryPlugin('cookie-plugin');
        $this->context->controller->addjqueryPlugin('fancybox');

        $this->context->controller->addCSS(array(
            _THEME_CSS_DIR_.'category.css' => 'all',
            _THEME_CSS_DIR_.'product_list.css' => 'all',
        ));
    }


    /**
     * This method check for existing other third party plugins in store
     * and if such a plugins exists we are preparing them to use.
     */
    protected function supportThirdPartyPlugins()
    {
        if (file_exists(_PS_MODULE_DIR_ . 'smartshortcode/smartshortcode.php')) {
            require_once(_PS_MODULE_DIR_ . 'smartshortcode/smartshortcode.php');
        }

        if ((bool)Module::isEnabled('jscomposer')) {
            $this->rbBlogPost->content = JsComposer::do_shortcode(
            	$this->rbBlogPost->content
            );
                   
            if (vc_mode() === 'page_editable') {
                $this->rbBlogPost->content = call_user_func(
                	JsComposer::$front_editor_actions['vc_content'],
                	$this->rbBlogPost->content
                );
            }
        }

        if ((bool)Module::isEnabled('smartshortcode')) {
            $smartshortcode = Module::getInstanceByName('smartshortcode');
            $this->checkForSmartShortcodeAddons();

            $this->rbBlogPost->content = $smartshortcode->parse(
            	$this->rbBlogPost->content
            );
        }
    }

    /**
     * Return RbBlogPost object
     * @return object
     */
    public function getPost()
    {
        return $this->rbBlogPost;
    }
}
