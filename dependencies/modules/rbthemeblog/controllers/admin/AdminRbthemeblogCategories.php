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

require_once _PS_MODULE_DIR_ . 'rbthemeblog/rbthemeblog.php';

class AdminRbthemeblogCategoriesController extends ModuleAdminController
{
	protected $position_identifier = 'id_rbblog_category';

	public function __construct()
    {
        $this->table = 'rbblog_category';
        $this->className = 'RbBlogCategory';
        $this->lang = true;
        $this->addRowAction('view');
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->bootstrap = true;
        $this->_where = 'AND a.`id_parent` = 0';
        $this->_orderBy = 'position';

        parent::__construct();

        $this->bulk_actions = array(
        	'delete' => array(
        		'text' => $this->l('Delete selected'),
        		'confirm' => $this->l('Delete selected items?')
        	)
        );

        $this->fields_list = array(
            'id_rbblog_category' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'width' => 30
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'width' => 'auto'
            ),
            'description' => array(
                'title' => $this->l('Description'),
                'width' => 500,
                'orderby' => false,
                'callback' => 'getDescriptionClean'
            ),
            'active' => array(
                'title' => $this->l('Displayed'),
                'width' => 25,
                'active' => 'status',
                'align' => 'center',
                'type' => 'bool',
                'orderby' => false
            ),
            'position' => array(
                'title' => $this->l('Position'),
                'width' => 40,
                'filter_key' => 'a!position',
                'position' => 'position'
            )
        );
    }


    public function renderList()
    {
        $this->initToolbar();
        $this->addRowAction('details');

        return parent::renderList();
    }

    public function getDescriptionClean($description, $row)
    {
        return strip_tags(stripslashes($description));
    }

    public function init()
    {
        parent::init();
        Shop::addTableAssociation($this->table, array('type' => 'shop'));

        if (Shop::getContext() == Shop::CONTEXT_SHOP) {
            $this->_join .= ' LEFT JOIN `'._DB_PREFIX_.'rbblog_category_shop` sa ON (a.`id_rbblog_category` = sa.`id_rbblog_category` AND sa.id_shop = '.(int)$this->context->shop->id.') ';
        }

        if (Shop::getContext() == Shop::CONTEXT_SHOP && Shop::isFeatureActive()) {
            $this->_where = ' AND sa.`id_shop` = '.(int)Context::getContext()->shop->id;
        }

        if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP) {
            unset($this->fields_list['position']);
        }
    }

    public function initFormToolBar()
    {
        unset($this->toolbar_btn['back']);

        $this->toolbar_btn['save-and-stay'] = array(
            'short' => 'SaveAndStay',
            'href' => '#',
            'desc' => $this->l('Save and stay'),
        );

        $this->toolbar_btn['back'] = array(
            'href' => self::$currentIndex.'&token='.Tools::getValue('token'),
            'desc' => $this->l('Back to list'),
        );
    }

    public function renderForm()
    {
        $this->initFormToolBar();

        if (!$this->loadObject(true)) {
            return;
        }

        $cover = false;
        $obj = $this->loadObject(true);

        if (isset($obj->id)) {
            $this->display = 'edit';

            $cover = ImageManager::thumbnail(
            	_PS_MODULE_DIR_ . 'rbthemeblog/covers_cat/'.$obj->id.'.'.$obj->cover,
            	'rbthemeblog_cat_'.$obj->id.'.'.$obj->cover,
            	350,
            	$obj->cover,
            	false
            );
        } else {
            $this->display = 'add';
        }

        $this->fields_value = array(
            'cover' => $cover ? $cover : false,
            'cover_size' => $cover ? filesize(_PS_MODULE_DIR_ . 'rbthemeblog/covers_cat/'.$obj->id.'.'.$obj->cover) / 1000 : false,
        );
        
        $categories = RbBlogCategory::getCategories(
        	$this->context->language->id,
        	true,
        	true
        );

        array_unshift(
        	$categories,
        	array('id' => 0, 'name' => $this->l('No parent'))
        );

        foreach ($categories as $key => $category) {
            if (isset($obj->id) && $obj->id) {
                if ($category['id'] == $obj->id_rbblog_category) {
                    unset($category[$key]);
                }
            }
        }

        $this->fields_form = array(
            'legend' => array(
                'title' => $this->l('Category'),
                'image' => '../img/admin/tab-categories.gif'
            ),
            'input' => array(
                array(
                    'type' => 'select',
                    'label' => $this->l('Parent Category:'),
                    'name' => 'id_parent',
                    'required' => true,
                    'options' => array(
                        'id' => 'id',
                        'query' => $categories,
                        'name' => 'name'
                        )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Name:'),
                    'name' => 'name',
                    'required' => true,
                    'lang' => true,
                    'class' => 'copy2friendlyUrl',
                ),
                array(
                    'type' => 'textarea',
                    'label' => $this->l('Description:'),
                    'name' => 'description',
                    'lang' => true,
                    'rows' => 5,
                    'cols' => 40,
                    'autoload_rte' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Meta title:'),
                    'name' => 'meta_title',
                    'lang' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Meta description:'),
                    'name' => 'meta_description',
                    'lang' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Meta keywords:'),
                    'name' => 'meta_keywords',
                    'lang' => true,
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Friendly URL:'),
                    'name' => 'link_rewrite',
                    'required' => true,
                    'lang' => true,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Displayed'),
                    'name' => 'active',
                    'required' => false,
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enabled')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        )
                    )
                ),
                array(
                    'type' => 'file',
                    'label' => $this->l('Category image:'),
                    'display_image' => true,
                    'name' => 'cover',
                    'desc' => $this->l('Upload a image from your computer.')
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
            )
        );
        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = array(
                'type' => 'shop',
                'label' => $this->l('Shop association:'),
                'name' => 'checkBoxShopAsso',
            );
        }

        $this->tpl_form_vars['PS_ALLOW_ACCENTED_CHARS_URL'] = (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');
        $this->tpl_form_vars['PS_FORCE_FRIENDLY_PRODUCT'] = (int)Configuration::get('PS_FORCE_FRIENDLY_PRODUCT');

        return parent::renderForm();
    }

    public function processDelete()
    {
        if ($this->tabAccess['delete'] === '1') {
            if (RbBlogCategory::getNbCats() == 1) {
                $this->errors[] = $this->l('You cannot remove this category because this is last category already used by module.');
            } else {
                return parent::processDelete();
            }
        } else {
            $this->errors[] = Tools::displayError(
            	'You do not have permission to delete this.'
            );
        }

        return false;
    }

    public function processAdd()
    {
        $object = parent::processAdd();

        if (isset($_FILES['cover']) && $_FILES['cover']['size'] > 0) {
            $object->cover = pathinfo(
            	$_FILES['cover']['name'],
            	PATHINFO_EXTENSION
            );

            $object->update();
        }

        if (!empty($object->cover)) {
            $this->createCover($_FILES['cover']['tmp_name'], $object);
        }

        $this->updateAssoShop($object->id);

        return true;
    }

    public function processUpdate()
    {
        $object = parent::processUpdate();

        if (isset($_FILES['cover']) && $_FILES['cover']['size'] > 0) {
            $object->cover = pathinfo(
            	$_FILES['cover']['name'],
            	PATHINFO_EXTENSION
            );

            $object->update();
        }

        if (!empty($object->cover) &&
        	isset($_FILES['cover']) &&
        	$_FILES['cover']['size'] > 0
        ) {
            $this->createCover($_FILES['cover']['tmp_name'], $object);
        }

        $this->updateAssoShop($object->id);

        return true;
    }

    public function postProcess()
    {
        if (Tools::isSubmit('viewrbblog_category') &&
        	($id_rbblog_category = (int)Tools::getValue('id_rbblog_category')) &&
        	($rbBlogCategory = new RbBlogCategory($id_rbblog_category, (int) $this->context->language->id)) &&
        	Validate::isLoadedObject($rbBlogCategory)
        ) {
            Tools::redirectAdmin($rbBlogCategory->getObjectLink((int) $this->context->language->id));
        }

        if (Tools::isSubmit('deleteCover')) {
            $this->deleteCover((int)Tools::getValue('id_rbblog_category'));
        }

        if (($id_rbblog_category = (int)Tools::getValue('id_rbblog_category')) &&
        	($direction = Tools::getValue('move')) &&
        	Validate::isLoadedObject($rbBlogCategory = new RbBlogCategory($id_rbblog_category))
        ) {
            if ($rbBlogCategory->move($direction)) {
                Tools::redirectAdmin(self::$currentIndex.'&token='.$this->token);
            }
        } elseif (Tools::getValue('position') && !Tools::isSubmit('submitAdd'.$this->table)) {
            if ($this->tabAccess['edit'] !== '1') {
                $this->errors[] = Tools::displayError('You do not have permission to edit this.');
            } elseif (!Validate::isLoadedObject($object = new RbBlogCategory((int)Tools::getValue($this->identifier)))) {
                $this->errors[] = Tools::displayError('An error occurred while updating the status for an object.').
                    ' <b>'.$this->table.'</b> '.Tools::displayError('(cannot load object)');
            }
            if (!$object->updatePosition((int)Tools::getValue('way'), (int)Tools::getValue('position'))) {
                $this->errors[] = Tools::displayError('Failed to update the position.');
            } else {
                Tools::redirectAdmin(self::$currentIndex.'&conf=5&token='.Tools::getAdminTokenLite('AdminRbthemeblogCategories'));
            }
        } else {
            if (!Tools::isSubmit('id_rbblog_category')) {
                $_POST['position'] = RbBlogCategory::getNbCats(Tools::getValue('id_parent'));
            }
        }

        return parent::postProcess();
    }

    public function ajaxProcessUpdatePositions()
    {
        $way = (int)(Tools::getValue('way'));
        $id_rbblog_category = (int)(Tools::getValue('id'));
        $positions = Tools::getValue('rbblog_category');

        foreach ($positions as $position => $value) {
            $pos = explode('_', $value);
            $id_rbblog_category = (int)$pos[2];

            if ((int)$id_rbblog_category > 0) {
                if ($rbBlogCategory = new RbBlogCategory($id_simpleblog_category)) {
                    $rbBlogCategory->position = $position + 1;

                    if ($rbBlogCategory->update()) {
                        echo 'ok position '.(int)$position.' for category '.(int)$rbBlogCategory->id.'\r\n';
                    }
                } else {
                    echo '{"hasError" : true, "errors" : "This category ('.(int)$id_rbblog_category.') cant be loaded"}';
                }
            }
        }
    }

    public function createCover($img = null, $object = null)
    {
        if (!isset($img)) {
            Tools::displayError('No image to process');
        }

        $categoryImgX = Configuration::get('RBTHEMEBLOG_CATEGORY_IMAGE_X');
        $categoryImgY = Configuration::get('RBTHEMEBLOG_CATEGORY_IMAGE_Y');

        if (isset($object) && Validate::isLoadedObject($object)) {
            $fileTmpLoc = $img;

            $origPath = _PS_MODULE_DIR_ . 'rbthemeblog/covers_cat/'.$object->id.'.'.$object->cover;

            $tmp_location = _PS_TMP_IMG_DIR_.'rbthemeblog_cat_'.$object->id.'.'.$object->cover;

            if (file_exists($tmp_location)) {
                @unlink($tmp_location);
            }
        
            try {
                $orig = PhpThumbFactory::create($fileTmpLoc);
            } catch (Exception $e) {
                echo $e;
            }

            $orig->adaptiveResize($categoryImgX, $categoryImgY);
            
            return $orig->save($origPath) && ImageManager::thumbnail(
            	_PS_MODULE_DIR_ . 'rbthemeblog/covers_cat/'.$object->id.'.'.$object->cover,
            	'rbthemeblog_cat_'.$object->id.'.'.$object->cover,
            	350,
            	$object->cover
            );
        }
    }

    public function deleteCover($id)
    {
        $object = new RbBlogCategory($id, Context::getContext()->language->id);
        $tmp_location = _PS_TMP_IMG_DIR_.'rbthemeblog_cat_'.$object->id.'.'.$object->cover;

        if (file_exists($tmp_location)) {
            @unlink($tmp_location);
        }

        $orig_location = _PS_MODULE_DIR_ . 'rbthemeblog/covers_cat/'.$object->id.'.'.$object->cover;
    
        if (file_exists($orig_location)) {
            @unlink($orig_location);
        }

        $object->cover = null;
        $object->update();

        Tools::redirectAdmin(
        	self::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminRbthemeblogCategories').'&conf=7'
        );
    }
}
