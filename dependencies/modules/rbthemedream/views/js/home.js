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
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/
(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports) {
	var HandleAddDuplicateBehavior;
	HandleAddDuplicateBehavior = Marionette.Behavior.extend({

	onChildviewClickNew: function(childView) {
		var currentIndex = childView.$el.index() + 1;
		this.addChild({at: currentIndex });
	},

	onRequestNew: function() {
		this.addChild();
	},

	addChild: function(options) {
		if (this.view.isCollectionFilled()) {
			return;
		}

		options = options || {};

		var newItem = {
			id: rb.helpers.getUniqueID(),
			elType: this.view.getChildType()[0],
			settings: {},
			elements: []
		};

		this.view.addChildModel(newItem, options);
	}
});

module.exports = HandleAddDuplicateBehavior;

},{}],2:[function(require,module,exports){
var HandleElementsRelation;

HandleElementsRelation = Marionette.Behavior.extend({

	onRequestAdd: function( itemData, options ) {
		this._addChildElement( itemData, options );
	},

	_addChildElement: function( itemData, options ) {
		options = options || {};

		var myChildType = this.view.getChildType();

		if ( -1 === myChildType.indexOf( itemData.elType ) ) {
			delete options.at;

			this.view.children.last().triggerMethod( 'request:add', itemData, options );

			return;
		}

		var newModel = this.view.addChildModel( itemData, options ),
			newView = this.view.children.findByModel( newModel );

		if ( 'section' === newView.getElementType() && newView.isInner() ) {
			newView.addEmptyColumn();
		}

		newView.triggerMethod('open:live');
	}
});

module.exports = HandleElementsRelation;

},{}],3:[function(require,module,exports){
var HandleDuplicateBehavior;

HandleDuplicateBehavior = Marionette.Behavior.extend( {

	onChildviewClickDuplicate: function( childView ) {
		if ( this.view.isCollectionFilled() ) {
			return;
		}

		var currentIndex = this.view.collection.indexOf( childView.model ),
			newModel = childView.model.clone();

		this.view.addChildModel( newModel, { at: currentIndex } );
	}
} );

module.exports = HandleDuplicateBehavior;
},{}],4:[function(require,module,exports){
var HandleEditModeBehavior;

HandleEditModeBehavior = Marionette.Behavior.extend( {
	initialize: function() {
		this.listenTo( rb.channels.dataEditMode, 'switch', this.onEditModeSwitched );
	},

	onEditModeSwitched: function() {
		var activeMode = rb.channels.dataEditMode.request( 'activeMode' );

		this.view.$el.toggleClass( 'rb-active-mode', 'preview' !== activeMode );
	},

	onRender: function() {
		this.onEditModeSwitched();
	}
} );

module.exports = HandleEditModeBehavior;

},{}],5:[function(require,module,exports){
var HandleliveBehavior;

HandleliveBehavior = Marionette.Behavior.extend( {

	onClickEdit: function() {
		var activeMode = rb.channels.dataEditMode.request( 'activeMode' );

		if ( 'preview' === activeMode ) {
			return;
		}

		this.onOpenLive();
	},

	onOpenLive: function() {
		var currentPanelPageName = rb.getPanelView().getCurrentPageName();

		if ( 'live' === currentPanelPageName ) {
			var currentPanelPageView = rb.getPanelView().getCurrentPageView(),
				currentEditableModel = currentPanelPageView.model;

			if ( currentEditableModel === this.view.model ) {
				return;
			}
		}

		var elementData = rb.getElementData( this.view.model );

		rb.getPanelView().setPage( 'live', rb.translate( 'edit_element', [ elementData.title ] ), {
			model: this.view.model,
			editedElementView: this.view
		} );
	}
} );

module.exports = HandleliveBehavior;

},{}],6:[function(require,module,exports){
var ResizableBehavior;

ResizableBehavior = Marionette.Behavior.extend( {
	defaults: {
		handles: rb.config.is_rtl ? 'w' : 'e'
	},

	ui: {
		columnTitle: '.column-title'
	},

	events: {
		resizestart: 'onResizeStart',
		resizestop: 'onResizeStop',
		resize: 'onResize'
	},

	initialize: function() {
		Marionette.Behavior.prototype.initialize.apply( this, arguments );

		this.listenTo( rb.channels.dataEditMode, 'switch', this.onEditModeSwitched );
	},

	active: function() {
		var options = _.clone( this.options );

		delete options.behaviorClass;

		var $childViewContainer = this.getChildViewContainer(),
			defaultResizableOptions = {},
			resizableOptions = _.extend( defaultResizableOptions, options );

		$childViewContainer.resizable( resizableOptions );
	},

	deactivate: function() {
		if ( this.getChildViewContainer().resizable( 'instance' ) ) {
			this.getChildViewContainer().resizable( 'destroy' );
		}
	},

	onEditModeSwitched: function() {
		var activeMode = rb.channels.dataEditMode.request( 'activeMode' );

		if ( 'preview' !== activeMode ) {
			this.active();
		} else {
			this.deactivate();
		}
	},

	onRender: function() {
		// Call this method from other thread (non-block UI)
		_.defer( _.bind( this.onEditModeSwitched, this ) );
	},

	onDestroy: function() {
		this.deactivate();
	},

	onResizeStart: function( event ) {
		event.stopPropagation();

		this.view.triggerMethod( 'request:resize:start' );
	},

	onResizeStop: function( event ) {
		event.stopPropagation();

		this.view.triggerMethod( 'request:resize:stop' );
	},

	onResize: function( event, ui ) {
		event.stopPropagation();

		this.view.triggerMethod( 'request:resize', ui );
	},

	getChildViewContainer: function() {
		return this.$el;
	}
} );

module.exports = ResizableBehavior;

},{}],7:[function(require,module,exports){
var SortableBehavior;

SortableBehavior = Marionette.Behavior.extend( {
	defaults: {
		elChildType: 'widget'
	},

	events: {
		'sortstart': 'onSortStart',
		'sortreceive': 'onSortReceive',
		'sortupdate': 'onSortUpdate',
		'sortstop': 'onSortStop',
		'sortover': 'onSortOver',
		'sortout': 'onSortOut'
	},

	initialize: function() {
		this.listenTo( rb.channels.dataEditMode, 'switch', this.onEditModeSwitched );
		this.listenTo( rb.channels.deviceMode, 'change', this.onDeviceModeChange );
	},

	onEditModeSwitched: function() {
		var activeMode = rb.channels.dataEditMode.request( 'activeMode' );

		if ( 'preview' !== activeMode ) {
			this.active();
		} else {
			this.deactivate();
		}
	},

	onDeviceModeChange: function() {
		var deviceMode = rb.channels.deviceMode.request( 'currentMode' );

		if ( 'desktop' === deviceMode ) {
			this.active();
		} else {
			this.deactivate();
		}
	},

	onRender: function() {
		_.defer( _.bind( this.onEditModeSwitched, this ) );
	},

	onDestroy: function() {
		this.deactivate();
	},

	active: function() {
		if ( this.getChildViewContainer().sortable( 'instance' ) ) {
			return;
		}

		var $childViewContainer = this.getChildViewContainer(),
			defaultSortableOptions = {
				connectWith: $childViewContainer.selector,
				cursor: 'move',
				placeholder: 'rb-sortable-placeholder',
				cursorAt: {
					top: 20,
					left: 25
				},
				helper: _.bind( this._getSortableHelper, this )
			},
			sortableOptions = _.extend( defaultSortableOptions, this.view.getSortableOptions() );

		$childViewContainer.sortable( sortableOptions );
	},

	_getSortableHelper: function(event, $item)
	{
		var model = this.view.collection.get({
			cid: $item.data('model-cid')
		});

		return '<div style="height: 84px; width: 125px;" class="rb-sortable-helper rb-sortable-helper-' + model.get( 'elType' ) + '"><div class="icon"><i class="eicon-' + model.getIcon() + '"></i></div><div class="rb-element-title-wrapper"><div class="title">' + model.getTitle() + '</div></div></div>';
	},

	deactivate: function() {
		if ( this.getChildViewContainer().sortable( 'instance' ) ) {
			this.getChildViewContainer().sortable( 'destroy' );
		}
	},

	onSortStart: function( event, ui ) {
		event.stopPropagation();

		var model = this.view.collection.get( {
			cid: ui.item.data( 'model-cid' )
		} );

		if ( 'column' === this.options.elChildType ) {
			// the following code is just for touch
			ui.placeholder.addClass( 'rb-column' );

			var uiData = ui.item.data( 'sortableItem' ),
				uiItems = uiData.items,
				itemHeight = 0;

			uiItems.forEach( function( item ) {
				if ( item.item[0] === ui.item[0] ) {
					itemHeight = item.height;
					return false;
				}
			} );

			ui.placeholder.height( itemHeight );
		}

		rb.channels.data.trigger( model.get( 'elType' ) + ':drag:start' );

		rb.channels.data.reply( 'cache:' + model.cid, model );
	},

	onSortOver: function( event, ui ) {
		event.stopPropagation();

		var model = rb.channels.data.request( 'cache:' + ui.item.data( 'model-cid' ) );

		Backbone.$( event.target )
			.addClass( 'rb-draggable-over' )
			.attr( {
				'data-dragged-element': model.get( 'elType' ),
				'data-dragged-is-inner': model.get( 'isInner' )
			} );

		this.$el.addClass( 'rb-dragging-on-child' );
	},

	onSortOut: function( event ) {
		event.stopPropagation();

		Backbone.$( event.target )
			.removeClass( 'rb-draggable-over' )
			.removeAttr( 'data-dragged-element data-dragged-is-inner' );

		this.$el.removeClass( 'rb-dragging-on-child' );
	},

	onSortReceive: function( event, ui ) {
		event.stopPropagation();

		if ( this.view.isCollectionFilled() ) {
			Backbone.$( ui.sender ).sortable( 'cancel' );
			return;
		}

		var model = rb.channels.data.request( 'cache:' + ui.item.data( 'model-cid' ) ),
			draggedElType = model.get( 'elType' ),
			draggedIsInnerSection = 'section' === draggedElType && model.get( 'isInner' ),
			targetIsInnerColumn = 'column' === this.view.getElementType() && this.view.isInner();

		if ( draggedIsInnerSection && targetIsInnerColumn ) {
			Backbone.$( ui.sender ).sortable( 'cancel' );
			return;
		}

		var newIndex = ui.item.parent().children().index( ui.item ),
			newModel = new this.view.collection.model( model.toJSON( { copyHtmlCache: true } ) );

		this.view.addChildModel( newModel, { at: newIndex } );

		rb.channels.data.trigger( draggedElType + ':drag:end' );

		model.destroy();
	},

	onSortUpdate: function( event, ui ) {
		event.stopPropagation();

		var model = this.view.collection.get( ui.item.attr( 'data-model-cid' ) );
		if ( model ) {
			rb.channels.data.trigger( model.get( 'elType' ) + ':drag:end' );
		}
	},

	onSortStop: function( event, ui ) {
		event.stopPropagation();

		var $childElement = ui.item,
			collection = this.view.collection,
			model = collection.get( $childElement.attr( 'data-model-cid' ) ),
			newIndex = $childElement.parent().children().index( $childElement );

		if ( this.getChildViewContainer()[0] === ui.item.parent()[0] ) {
			if ( null === ui.sender && model ) {
				var oldIndex = collection.indexOf( model );

				if ( oldIndex !== newIndex ) {
					collection.remove( model );
					this.view.addChildModel( model, { at: newIndex } );

					rb.setFlagliveChange( true );
				}

				rb.channels.data.trigger( model.get( 'elType' ) + ':drag:end' );
			}
		}
	},

	onAddChild: function( view ) {
		view.$el.attr( 'data-model-cid', view.model.cid );
	},

	getChildViewContainer: function() {
		if ( 'function' === typeof this.view.getChildViewContainer ) {
			// CompositeView
			return this.view.getChildViewContainer( this.view );
		} else {
			// CollectionView
			return this.$el;
		}
	}
} );

module.exports = SortableBehavior;
},{}],8:[function(require,module,exports){
var TemplateLibraryTemplateModel = require( 'rb-templates/models/template' ),
	TemplateLibraryCollection;

TemplateLibraryCollection = Backbone.Collection.extend( {
	model: TemplateLibraryTemplateModel
} );

module.exports = TemplateLibraryCollection;

},{"rb-templates/models/template":10}],9:[function(require,module,exports){
var TemplateLibraryLayoutView = require( 'rb-templates/views/layout' ),
	TemplateLibraryCollection = require( 'rb-templates/collections/templates' ),
	TemplateLibraryManager;

TemplateLibraryManager = function() {
	var self = this,
		modal,
		deleteDialog,
		errorDialog,
		layout,
		templatesCollection;

	var initLayout = function() {
		layout = new TemplateLibraryLayoutView();
	};

	this.deleteTemplate = function( templateModel ) {
		var dialog = self.getDeleteDialog();

		dialog.onConfirm = function() {
			rb.ajax.send( 'deleteTemplate', {
				data: {
					source: templateModel.get( 'source' ),
					template_id: templateModel.get( 'template_id' )
				},
				success: function() {
					templatesCollection.remove( templateModel, { silent: true } );

					self.showTemplates();
				}
			} );
		};

		dialog.show();
	};

	this.importTemplate = function( templateModel ) {
		layout.showLoadingView();

		rb.ajax.send( 'getTemplateContent', {
			data: {
				source: templateModel.get( 'source' ),
				post_id: rb.config.post_id,
				template_id: templateModel.get( 'template_id' )
			},
			success: function( data ) {
				self.getModal().hide();

				rb.getRegion( 'sections' ).currentView.addChildModel( data );
			},
			error: function( data ) {
				self.showErrorDialog( data.message );
			}
		} );
	};

	this.getDeleteDialog = function() {
		if ( ! deleteDialog ) {
			deleteDialog = rb.dialogsManager.createWidget( 'confirm', {
				id: 'rb-template-library-delete-dialog',
				headerMessage: rb.translate( 'delete_template' ),
				message: rb.translate( 'delete_template_confirm' ),
				strings: {
					confirm: rb.translate( 'delete' )
				}
			} );
		}

		return deleteDialog;
	};

	this.getErrorDialog = function() {
		if ( ! errorDialog ) {
			errorDialog = rb.dialogsManager.createWidget( 'alert', {
				id: 'rb-template-library-error-dialog',
				headerMessage: rb.translate( 'an_error_occurred' )
			} );
		}

		return errorDialog;
	};

	this.getModal = function() {
		if ( ! modal ) {
			modal = rb.dialogsManager.createWidget( 'rb-modal', {
				id: 'rb-template-library-modal',
				closeButton: false
			} );
		}

		return modal;
	};

	this.getLayout = function() {
		return layout;
	};

	this.getTemplatesCollection = function() {
		return templatesCollection;
	};

	this.requestRemoteTemplates = function( callback, forceUpdate ) {
		if ( templatesCollection && ! forceUpdate ) {
			if ( callback ) {
				callback();
			}

			return;
		}

		rb.ajax.send( 'GetTemplates', {
			success: function( data ) {
				templatesCollection = new TemplateLibraryCollection( data );

				if ( callback ) {
					callback();
				}
			}
		} );
	};

	this.startModal = function( onModalReady ) {
		self.getModal().show();

		self.setTemplatesSource( 'local' );

		if ( ! layout ) {
			initLayout();
		}

		layout.showLoadingView();

		self.requestRemoteTemplates( function() {
			if ( onModalReady ) {
				onModalReady();
			}
		} );
	};

	this.setTemplatesSource = function( source, trigger ) {
		var channel = rb.channels.templates;

		channel.reply( 'filter:source', source );

		if ( trigger ) {
			channel.trigger( 'filter:change' );
		}
	};

	this.showTemplates = function() {
		layout.showTemplatesView( templatesCollection );
	};

	this.showErrorDialog = function( errorMessage ) {
		self.getErrorDialog()
		    .setMessage( rb.translate( 'templates_request_error' ) + '<div id="rb-template-library-error-info">' + errorMessage + '</div>' )
		    .show();
	};
};

module.exports = new TemplateLibraryManager();

},{"rb-templates/collections/templates":8,"rb-templates/views/layout":11}],10:[function(require,module,exports){
var TemplateLibraryTemplateModel;

TemplateLibraryTemplateModel = Backbone.Model.extend( {
	defaults: {
		template_id: 0,
		name: '',
		title: '',
		source: '',
		type: '',
		author: '',
		thumbnail: '',
		url: '',
		export_link: '',
		categories: [],
		keywords: []
	}
} );

module.exports = TemplateLibraryTemplateModel;

},{}],11:[function(require,module,exports){
var TemplateLibraryHeaderView = require( 'rb-templates/views/parts/header' ),
	TemplateLibraryHeaderLogoView = require( 'rb-templates/views/parts/header-parts/logo' ),
	TemplateLibraryHeaderSaveView = require( 'rb-templates/views/parts/header-parts/save' ),
	TemplateLibraryHeaderLoadView = require( 'rb-templates/views/parts/header-parts/load' ),
	TemplateLibraryHeaderMenuView = require( 'rb-templates/views/parts/header-parts/menu' ),
	TemplateLibraryHeaderPreviewView = require( 'rb-templates/views/parts/header-parts/preview' ),
	TemplateLibraryHeaderBackView = require( 'rb-templates/views/parts/header-parts/back' ),
	TemplateLibraryLoadingView = require( 'rb-templates/views/parts/loading' ),
	TemplateLibraryCollectionView = require( 'rb-templates/views/parts/templates' ),
	TemplateLibrarySaveTemplateView = require( 'rb-templates/views/parts/save-template' ),
	TemplateLibraryLoadTemplateView = require( 'rb-templates/views/parts/load-template' ),
	TemplateLibraryPreviewView = require( 'rb-templates/views/parts/preview' ),
	TemplateLibraryLayoutView;

TemplateLibraryLayoutView = Marionette.LayoutView.extend( {
	el: '#rb-template-library-modal',

	regions: {
		modalContent: '.dialog-message',
		modalHeader: '.dialog-widget-header'
	},

	initialize: function() {
		this.getRegion( 'modalHeader' ).show( new TemplateLibraryHeaderView() );
	},

	getHeaderView: function() {
		return this.getRegion( 'modalHeader' ).currentView;
	},

	showLoadingView: function() {
		this.getRegion( 'modalContent' ).show( new TemplateLibraryLoadingView() );
	},

	showTemplatesView: function( templatesCollection ) {
		this.getRegion( 'modalContent' ).show( new TemplateLibraryCollectionView( {
			collection: templatesCollection
		} ) );

		var headerView = this.getHeaderView();

		headerView.tools.show( new TemplateLibraryHeaderSaveView() );
		headerView.tools2.show( new TemplateLibraryHeaderLoadView() );
		headerView.logoArea.show( new TemplateLibraryHeaderLogoView() );
	},

	showSaveTemplateView: function( sectionID ) {
		this.getRegion( 'modalContent' ).show( new TemplateLibrarySaveTemplateView( { sectionID: sectionID } ) );

		var headerView = this.getHeaderView();

		headerView.tools.reset();
		headerView.tools2.show( new TemplateLibraryHeaderLoadView() );
		headerView.menuArea.show( new TemplateLibraryHeaderMenuView() );
		headerView.logoArea.show( new TemplateLibraryHeaderLogoView() );
	},

	showLoadTemplateView: function( sectionID ) {
		this.getRegion( 'modalContent' ).show( new TemplateLibraryLoadTemplateView( { sectionID: sectionID } ) );

		var headerView = this.getHeaderView();

		headerView.tools2.reset();
		headerView.tools.show( new TemplateLibraryHeaderSaveView() );
		headerView.menuArea.show( new TemplateLibraryHeaderMenuView() );
		headerView.logoArea.show( new TemplateLibraryHeaderLogoView() );
	},

	showPreviewView: function( templateModel ) {
		this.getRegion( 'modalContent' ).show( new TemplateLibraryPreviewView( {
			url: templateModel.get( 'url' )
		} ) );

		var headerView = this.getHeaderView();

		headerView.menuArea.reset();

		headerView.tools.show( new TemplateLibraryHeaderPreviewView( {
			model: templateModel
		} ) );

		headerView.logoArea.show( new TemplateLibraryHeaderBackView() );
	}
} );

module.exports = TemplateLibraryLayoutView;

},{"rb-templates/views/parts/header":18,"rb-templates/views/parts/header-parts/back":12,"rb-templates/views/parts/header-parts/load":13,"rb-templates/views/parts/header-parts/logo":14,"rb-templates/views/parts/header-parts/menu":15,"rb-templates/views/parts/header-parts/preview":16,"rb-templates/views/parts/header-parts/save":17,"rb-templates/views/parts/load-template":19,"rb-templates/views/parts/loading":20,"rb-templates/views/parts/preview":21,"rb-templates/views/parts/save-template":22,"rb-templates/views/parts/templates":24}],12:[function(require,module,exports){
var TemplateLibraryHeaderBackView;

TemplateLibraryHeaderBackView = Marionette.ItemView.extend( {
	template: '#dd-rb-template-library-header-back',

	id: 'rb-template-library-header-preview-back',

	events: {
		'click': 'onClick'
	},

	onClick: function() {
		rb.templates.showTemplates();
	}
} );

module.exports = TemplateLibraryHeaderBackView;

},{}],13:[function(require,module,exports){
var TemplateLibraryHeaderLoadView;

TemplateLibraryHeaderLoadView = Marionette.ItemView.extend( {
	template: '#dd-rb-template-library-header-load',

	id: 'rb-template-library-header-load',

	className: 'rb-template-library-header-item',

	events: {
		'click': 'onClick'
	},

	onClick: function() {
		rb.templates.getLayout().showLoadTemplateView();
	}
} );

module.exports = TemplateLibraryHeaderLoadView;

},{}],14:[function(require,module,exports){
var TemplateLibraryHeaderLogoView;

TemplateLibraryHeaderLogoView = Marionette.ItemView.extend( {
	template: '#dd-rb-template-library-header-logo',

	id: 'rb-template-library-header-logo',

	events: {
		'click': 'onClick'
	},

	onClick: function() {
		rb.templates.setTemplatesSource( 'local' );
		rb.templates.showTemplates();
	}
} );

module.exports = TemplateLibraryHeaderLogoView;

},{}],15:[function(require,module,exports){
var TemplateLibraryHeaderMenuView;

TemplateLibraryHeaderMenuView = Marionette.ItemView.extend( {
	options: {
		activeClass: 'rb-active'
	},

	template: '#dd-rb-template-library-header-menu',

	id: 'rb-template-library-header-menu',

	ui: {
		menuItems: '.rb-template-library-menu-item'
	},

	events: {
		'click @ui.menuItems': 'onMenuItemClick'
	},

	$activeItem: null,

	activateMenuItem: function( $item ) {
		var activeClass = this.getOption( 'activeClass' );

		if ( this.$activeItem === $item ) {
			return;
		}

		if ( this.$activeItem ) {
			this.$activeItem.removeClass( activeClass );
		}

		$item.addClass( activeClass );

		this.$activeItem = $item;
	},

	onRender: function() {
		var currentSource = rb.channels.templates.request( 'filter:source' ),
			$sourceItem = this.ui.menuItems.filter( '[data-template-source="' + currentSource + '"]' );

		this.activateMenuItem( $sourceItem );
	},

	onMenuItemClick: function( event ) {
		var item = event.currentTarget;

		this.activateMenuItem( Backbone.$( item ) );

		rb.templates.setTemplatesSource( 'local');
		rb.templates.showTemplates();
	}
} );

module.exports = TemplateLibraryHeaderMenuView;

},{}],16:[function(require,module,exports){
var TemplateLibraryHeaderPreviewView;

TemplateLibraryHeaderPreviewView = Marionette.ItemView.extend( {
	template: '#dd-rb-template-library-header-preview',

	id: 'rb-template-library-header-preview',

	ui: {
		insertButton: '#rb-template-library-header-preview-insert'
	},

	events: {
		'click @ui.insertButton': 'onInsertButtonClick'
	},

	onInsertButtonClick: function() {
		rb.templates.importTemplate( this.model );
	}
} );

module.exports = TemplateLibraryHeaderPreviewView;

},{}],17:[function(require,module,exports){
var TemplateLibraryHeaderSaveView;

TemplateLibraryHeaderSaveView = Marionette.ItemView.extend( {
	template: '#dd-rb-template-library-header-save',

	id: 'rb-template-library-header-save',

	className: 'rb-template-library-header-item',

	events: {
		'click': 'onClick'
	},

	onClick: function() {
		rb.templates.getLayout().showSaveTemplateView();
	}
} );

module.exports = TemplateLibraryHeaderSaveView;

},{}],18:[function(require,module,exports){
var TemplateLibraryHeaderView;

TemplateLibraryHeaderView = Marionette.LayoutView.extend( {

	id: 'rb-template-library-header',

	template: '#dd-rb-template-library-header',

	regions: {
		logoArea: '#rb-template-library-header-logo-area',
		tools: '#rb-template-library-header-tools',
		tools2: '#rb-template-library-header-tools2',
		menuArea: '#rb-template-library-header-menu-area'
	},

	ui: {
		closeModal: '#rb-template-library-header-close-modal'
	},

	events: {
		'click @ui.closeModal': 'onCloseModalClick'
	},

	onCloseModalClick: function() {
		rb.templates.getModal().hide();
	}
} );

module.exports = TemplateLibraryHeaderView;

},{}],19:[function(require,module,exports){
var TemplateLibraryLoadTemplateView;

TemplateLibraryLoadTemplateView = Marionette.ItemView.extend( {
	id: 'rb-template-library-load-template',

	template: '#dd-rb-template-library-load-template',

	ui: {
		form: '#rb-template-library-load-template-form',
		submitButton: '#rb-template-library-load-template-submit',
		fileInput: '#rb-template-library-load-template-file',
		fileInputNice: '#rb-template-library-load-btn-file'
	},

	events: {
		'submit @ui.form': 'onFormSubmit',
		'change @ui.fileInput': 'onFileChange'
	},


	templateHelpers: function() {
		return {
			sectionID: this.getOption( 'sectionID' )
		};
	},

	onFileChange: function() {
		$(this.ui.fileInputNice).text($(this.ui.fileInput)[0].files[0].name);
	},

	onFormSubmit: function( event ) {
		event.preventDefault();

		this.ui.submitButton.addClass( 'rb-button-state' );

		rb.ajax.send( 'importTemplate', {
			data: new FormData( this.ui.form[ 0 ] ),
			processData: false,
			contentType: false,
			success: function( data ) {
				rb.templates.getTemplatesCollection().add( data );

				rb.templates.setTemplatesSource( 'local' );

				rb.templates.showTemplates();
			},
			error: function( data ) {
				rb.templates.showErrorDialog( data.message );
			}
		} );
	}
} );

module.exports = TemplateLibraryLoadTemplateView;

},{}],20:[function(require,module,exports){
var TemplateLibraryLoadingView;

TemplateLibraryLoadingView = Marionette.ItemView.extend( {
	id: 'rb-template-library-loading',

	template: '#dd-rb-template-library-loading'
} );

module.exports = TemplateLibraryLoadingView;

},{}],21:[function(require,module,exports){
var TemplateLibraryPreviewView;

TemplateLibraryPreviewView = Marionette.ItemView.extend( {
	template: '#dd-rb-template-library-preview',

	id: 'rb-template-library-preview',

	ui: {
		iframe: '> iframe'
	},

	onRender: function() {
		this.ui.iframe.attr( 'src', this.getOption( 'url' ) );
	}
} );

module.exports = TemplateLibraryPreviewView;

},{}],22:[function(require,module,exports){
var TemplateLibrarySaveTemplateView;

TemplateLibrarySaveTemplateView = Marionette.ItemView.extend( {
	id: 'rb-template-library-save-template',

	template: '#dd-rb-template-library-save-template',

	ui: {
		form: '#rb-template-library-save-template-form',
		submitButton: '#rb-template-library-save-template-submit'
	},

	events: {
		'submit @ui.form': 'onFormSubmit'
	},

	templateHelpers: function() {
		return {
			sectionID: this.getOption( 'sectionID' )
		};
	},

	onFormSubmit: function( event ) {
		event.preventDefault();

		var formData = this.ui.form.rbSerializeObject(),
			elementsData = rb.helpers.cloneObject( rb.elements.toJSON() ),
			sectionID = this.getOption( 'sectionID' ),
			saveType = sectionID ? 'section' : 'page';

		if ( 'section' === saveType ) {
			elementsData = [ _.findWhere( elementsData, { id: sectionID } ) ];
		}

		_.extend( formData, {
			data: JSON.stringify( elementsData ),
			source: 'local',
			type: saveType
		} );

		this.ui.submitButton.addClass( 'rb-button-state' );

		rb.ajax.send( 'saveTemplate', {
			data: formData,
			success: function( data ) {
				rb.templates.getTemplatesCollection().add( data );

				rb.templates.setTemplatesSource( 'local' );

				rb.templates.showTemplates();
			},
			error: function( data ) {
				rb.templates.showErrorDialog( data.message );
			}
		} );
	}
} );

module.exports = TemplateLibrarySaveTemplateView;

},{}],23:[function(require,module,exports){
var TemplateLibraryTemplatesEmptyView;

TemplateLibraryTemplatesEmptyView = Marionette.ItemView.extend( {
	id: 'rb-template-library-templates-empty',

	template: '#dd-rb-template-library-templates-empty'
} );

module.exports = TemplateLibraryTemplatesEmptyView;

},{}],24:[function(require,module,exports){
var TemplateLibraryTemplateLocalView = require( 'rb-templates/views/template/local' ),
	TemplateLibraryTemplatesEmptyView = require( 'rb-templates/views/parts/templates-empty' ),
	TemplateLibraryCollectionView;

TemplateLibraryCollectionView = Marionette.CompositeView.extend( {
	template: '#dd-rb-template-library-templates',

	id: 'rb-template-library-templates',

	childViewContainer: '#rb-template-library-templates-container',

	emptyView: TemplateLibraryTemplatesEmptyView,

	getChildView: function( childModel ) {
		return TemplateLibraryTemplateLocalView;
	},

	initialize: function() {
		this.listenTo( rb.channels.templates, 'filter:change', this._renderChildren );
	},

	filterByName: function( model ) {
		var filterValue = rb.channels.templates.request( 'filter:text' );

		if ( ! filterValue ) {
			return true;
		}

		filterValue = filterValue.toLowerCase();

		if ( model.get( 'title' ).toLowerCase().indexOf( filterValue ) >= 0 ) {
			return true;
		}

		return _.any( model.get( 'keywords' ), function( keyword ) {
			return keyword.toLowerCase().indexOf( filterValue ) >= 0;
		} );
	},

	filterBySource: function( model ) {
		var filterValue = rb.channels.templates.request( 'filter:source' );

		if ( ! filterValue ) {
			return true;
		}

		return filterValue === model.get( 'source' );
	},

	filter: function( childModel ) {
		return this.filterByName( childModel ) && this.filterBySource( childModel );
	},

	onRenderCollection: function() {
		var isEmpty = this.children.isEmpty();

		this.$childViewContainer.attr( 'data-template-source', isEmpty ? 'empty' : rb.channels.templates.request( 'filter:source' ) );
	}
} );

module.exports = TemplateLibraryCollectionView;

},{"rb-templates/views/parts/templates-empty":23,"rb-templates/views/template/local":26}],25:[function(require,module,exports){
var TemplateLibraryTemplateView;

TemplateLibraryTemplateView = Marionette.ItemView.extend( {
	className: function() {
		return 'rb-template-library-template rb-template-library-template-' + this.model.get( 'source' );
	},

	ui: function() {
		return {
			insertButton: '.rb-template-library-template-insert',
			previewButton: '.rb-template-library-template-preview'
		};
	},

	events: function() {
		return {
			'click @ui.insertButton': 'onInsertButtonClick',
			'click @ui.previewButton': 'onPreviewButtonClick'
		};
	},

	onInsertButtonClick: function() {
		rb.templates.importTemplate( this.model );
	}
} );

module.exports = TemplateLibraryTemplateView;

},{}],26:[function(require,module,exports){
var TemplateLibraryTemplateView = require( 'rb-templates/views/template/base' ),
	TemplateLibraryTemplateLocalView;

TemplateLibraryTemplateLocalView = TemplateLibraryTemplateView.extend( {
	template: '#dd-rb-template-library-template-local',

	ui: function() {
		return _.extend( TemplateLibraryTemplateView.prototype.ui.apply( this, arguments ), {
			deleteButton: '.rb-template-library-template-delete'
		} );
	},

	events: function() {
		return _.extend( TemplateLibraryTemplateView.prototype.events.apply( this, arguments ), {
			'click @ui.deleteButton': 'onDeleteButtonClick'
		} );
	},

	onDeleteButtonClick: function() {
		rb.templates.deleteTemplate( this.model );
	},

	onPreviewButtonClick: function() {
		open( this.model.get( 'url' ), '_blank' );
	}
} );

module.exports = TemplateLibraryTemplateLocalView;

},{"rb-templates/views/template/base":25}],27:[function(require,module,exports){
/* global rbConfig */
var App;

Marionette.TemplateCache.prototype.compileTemplate = function( rawTemplate, options ) {
	options = {
		evaluate: /<#([\s\S]+?)#>/g,
		interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
		escape: /\{\{([^\}]+?)\}\}(?!\})/g
	};

	return _.template( rawTemplate, options );
};

App = Marionette.Application.extend( {
	helpers: require( 'rb-utils/helpers' ),
	schemes: require( 'rb-utils/schemes' ),
	presetsFactory: require( 'rb-utils/presets-factory' ),
	modals: require( 'rb-utils/modals' ),
	introduction: require( 'rb-utils/introduction' ),
	templates: require( 'rb-templates/manager' ),
	ajax: require( 'rb-utils/ajax' ),

	channels: {
		live: Backbone.Radio.channel( 'rb:live' ),
		data: Backbone.Radio.channel( 'rb:data' ),
		panelElements: Backbone.Radio.channel( 'rb:panelElements' ),
		dataEditMode: Backbone.Radio.channel( 'rb:editmode' ),
		deviceMode: Backbone.Radio.channel( 'rb:deviceMode' ),
		templates: Backbone.Radio.channel( 'rb:templates' )
	},

	// Private Members
	_controlsItemView: null,

	_defaultDeviceMode: 'desktop',

	getElementData: function( modelElement ) {
		var elType = modelElement.get( 'elType' );

		if ( 'widget' === elType ) {
			var widgetType = modelElement.get( 'widgetType' );

			if ( ! this.config.widgets[ widgetType ] ) {
				return false;
			}

			return this.config.widgets[ widgetType ];
		}

		if ( ! this.config.elements[ elType ] ) {
			return false;
		}

		return this.config.elements[ elType ];
	},

	getElementControls: function( modelElement ) {
		var elementData = this.getElementData( modelElement );

		if ( ! elementData ) {
			return false;
		}

		var elType = modelElement.get( 'elType' ),
			isInner = modelElement.get( 'isInner' );

		if ( 'widget' === elType ) {
			return elementData.controls;
		}

		return _.filter( elementData.controls, function( controlData ) {
			return ! ( isInner && controlData.hide_in_inner || ! isInner && controlData.hide_in_top );
		} );
	},

	getControlItemView: function( controlType ) {
		if ( null === this._controlsItemView ) {
			this._controlsItemView = {
				color: require( 'rb-views/controls/color' ),
				dimensions: require( 'rb-views/controls/dimensions' ),
				image_dimensions: require( 'rb-views/controls/image-dimensions' ),
				media: require( 'rb-views/controls/media' ),
				slider: require( 'rb-views/controls/slider' ),
				wysiwyg: require( 'rb-views/controls/wysiwyg' ),
				autocomplete_products: require( 'rb-views/controls/autocomplete-products' ),
				choose: require( 'rb-views/controls/choose' ),
				url: require( 'rb-views/controls/url' ),
				font: require( 'rb-views/controls/font' ),
				section: require( 'rb-views/controls/section' ),
				repeater: require( 'rb-views/controls/repeater' ),
				wp_widget: require( 'rb-views/controls/wp_widget' ),
				icon: require( 'rb-views/controls/icon' ),
				gallery: require( 'rb-views/controls/gallery' ),
				select2: require( 'rb-views/controls/select2' ),
				select_sort: require( 'rb-views/controls/select-sort' ),
				box_shadow: require( 'rb-views/controls/box-shadow' ),
				structure: require( 'rb-views/controls/structure' ),
				animation: require( 'rb-views/controls/animation' ),
				hover_animation: require( 'rb-views/controls/animation' )
			};

			this.channels.live.trigger( 'live:controls:initialize' );
		}

		return this._controlsItemView[ controlType ] || require( 'rb-views/controls/base' );
	},

	getPanelView: function() {
		return this.getRegion( 'panel' ).currentView;
	},

	initComponents: function() {
		this.initDialogsManager();

		this.modals.init();
		this.ajax.init();
	},

	initDialogsManager: function() {
		this.dialogsManager = new DialogsManager.Instance();
	},

	initPreview: function() {
		this.$previewWrapper = Backbone.$( '#rb-preview' );
		this.$previewResponsiveWrapper = Backbone.$( '#rb-preview-responsive-wrapper' );
		var previewIframeId = 'rb-preview-iframe';

		// Make sure the iFrame does not exist.

		if ( ! Backbone.$( '#' + previewIframeId ).length ) {
			var previewIFrame = document.createElement( 'iframe' );
			previewIFrame.id = previewIframeId;
			previewIFrame.src = this.config.view_link + '&' + ( new Date().getTime() );
			this.$previewResponsiveWrapper.append( previewIFrame );
		}
		this.$preview = Backbone.$('#' + previewIframeId);
		this.$preview.on( 'load', _.bind( this.onPreviewLoaded, this ) );
	},

	initFrontend: function() {
		rbFrontend.setScopeWindow( this.$preview[0].contentWindow );

		rbFrontend.init();
	},

	onStart: function() {
		NProgress.start();
		NProgress.inc( 0.2 );

		this.config = rbConfig;

		Backbone.Radio.DEBUG = false;
		Backbone.Radio.tuneIn( 'rb' );

		this.initComponents();

		// Init Base elements collection from the server
		var ElementModel = require('rb-models/element');

		this.elements = new ElementModel.Collection(this.config.data);

		this.initPreview();

		this.listenTo( this.channels.dataEditMode, 'switch', this.onEditModeSwitched );

		this.setWorkSaver();

		this.initClearPageDialog();
		this.initLostPageDialog();
		this.initIeEdgeDialog();

	},

	onPreviewLoaded: function() {
		NProgress.done();
		this.initFrontend();
		this.$previewContents = this.$preview.contents();
		var SectionsCollectionView = require('rb-views/sections'),
		PanelLayoutView = require('rb-layouts/panel/panel');
		var $previewrbEl = this.$previewContents.find('#rb');

		if (!$previewrbEl.length) {
			this.onPreviewElNotFound();
			return;
		}

		var iframeRegion = new Marionette.Region( {
			// Make sure you get the DOM object out of the jQuery object
			el: $previewrbEl[0]
		});

		this.schemes.init();
		this.schemes.printSchemesStyle();

		this.$previewContents.on( 'click', function( event ) {
			var $target = Backbone.$( event.target ),
				editMode = rb.channels.dataEditMode.request( 'activeMode' ),
				isClickInsiderb = !! $target.closest( '#rb' ).length,
				isTargetInsideDocument = this.contains( $target[0] );

			if ( isClickInsiderb && 'preview' !== editMode || ! isTargetInsideDocument ) {
				return;
			}

			if ( $target.closest( 'a' ).length ) {
				event.preventDefault();
			}

			if ( ! isClickInsiderb ) {
				rb.getPanelView().setPage( 'elements' );
			}
		} );

		this.addRegions( {
			sections: iframeRegion,
			panel: '#rb-panel'
		} );

		this.getRegion( 'sections' ).show( new SectionsCollectionView( {
			collection: this.elements
		} ) );

		this.getRegion( 'panel' ).show( new PanelLayoutView() );

		this.$previewContents
		    .children()
		    .addClass( 'rb-html' )
		    .children( 'body' )
		    .addClass( 'rb-live-active' );

		this.setResizablePanel();
		this.changeDeviceMode( this._defaultDeviceMode );
		Backbone.$( '#rb-loading' ).hide( 600 );

		_.defer(function() {
			rbFrontend.getScopeWindow().jQuery.holdReady( false );
		});

		this.enqueueTypographyFonts();
		this.trigger( 'preview:loaded' );

		var browserVersion = this.detectIE();

		if (browserVersion != false){
			this.getIeEdgeDialog().show();
		}
	},

	onEditModeSwitched: function() {
		var activeMode = rb.channels.dataEditMode.request( 'activeMode' );

		if ( 'preview' === activeMode ) {
			this.enterPreviewMode();
		} else {
			this.exitPreviewMode();
		}
	},

	onPreviewElNotFound: function() {
		var dialog = this.dialogsManager.createWidget( 'confirm', {
			id: 'rb-fatal-error-dialog',
			headerMessage: rb.translate('preview_el_not_found_header'),
			message: rb.translate('preview_el_not_found_message'),
			position: {
				my: 'center center',
				at: 'center center'
			},
            strings: {
				confirm: rb.translate('learn_more'),
				cancel: rb.translate('go_back')
            },
			onConfirm: function() {
				open(rb.config.maintance_url_settings, '_blank');
			},
			onCancel: function() {
				parent.history.go(-1);
			},
			hideOnButtonClick: false
		} );

		dialog.show();
	},

	setFlagliveChange: function( status ) {
		rb.channels.live.reply( 'live:changed', status );
		rb.channels.live.trigger( 'live:changed', status );
	},

	isliveChanged: function() {
		return ( true === rb.channels.live.request( 'live:changed' ) );
	},

	setWorkSaver: function() {
		Backbone.$( window ).on( 'beforeunload', function() {
			if ( rb.isliveChanged() ) {
				return rb.translate('before_unload_alert');
			}
		} );
	},

	setResizablePanel: function() {
		var self = this,
			side = rb.config.is_rtl ? 'right' : 'left';

		self.panel.$el.resizable( {
			handles: rb.config.is_rtl ? 'w' : 'e',
			minWidth: 200,
			maxWidth: 500,
			start: function() {
				self.$previewWrapper
					.addClass( 'ui-resizable-resizing' )
					.css( 'pointer-events', 'none' );
			},
			stop: function() {
				self.$previewWrapper
					.removeClass( 'ui-resizable-resizing' )
					.css( 'pointer-events', '' );

				rb.channels.data.trigger( 'scrollbar:update' );
			},
			resize: function( event, ui ) {
				self.$previewWrapper
					.css( side, ui.size.width );
			}
		} );
	},

	enterPreviewMode: function() {
		this.$previewContents
		    .find( 'body' )
		    .add( 'body' )
		    .removeClass( 'rb-live-active' )
		    .addClass( 'rb-live-preview' );

		// Handle panel resize
		this.$previewWrapper.css( rb.config.is_rtl ? 'right' : 'left', '' );

		this.panel.$el.css( 'width', '' );
	},

	exitPreviewMode: function() {
		this.$previewContents
		    .find( 'body' )
		    .add( 'body' )
		    .removeClass( 'rb-live-preview' )
		    .addClass( 'rb-live-active' );
	},

	saveBuilder: function(options) {
		options = _.extend({
			revision: 'draft',
			onSuccess: null
		}, options);

		NProgress.start();

		return this.ajax.send('SaveLive', {
	        data: {
		        page_id: this.config.post_id,
				id_lang: this.config.id_lang,
				page_type: this.config.page_type,
		        revision: options.revision,
		        data: JSON.stringify(rb.elements.toJSON())
	        },
			success: function(data) {
				NProgress.done();

				rb.setFlagliveChange(false);

				if (_.isFunction( options.onSuccess)) {
					options.onSuccess.call(this, data);
				}
			}
        });
	},

	changeDeviceMode: function( newDeviceMode ) {
		var oldDeviceMode = this.channels.deviceMode.request( 'currentMode' );

		if ( oldDeviceMode === newDeviceMode ) {
			return;
		}

		Backbone.$( 'body' )
			.removeClass( 'rb-device-' + oldDeviceMode )
			.addClass( 'rb-device-' + newDeviceMode );

		this.channels.deviceMode
			.reply( 'previousMode', oldDeviceMode )
			.reply( 'currentMode', newDeviceMode )
			.trigger( 'change' );

		Backbone.$( window ).trigger('changedDeviceMode');
	},

	initClearPageDialog: function() {
		var self = this,
			dialog;

		self.getClearPageDialog = function() {
			if ( dialog ) {
				return dialog;
			}

			dialog = this.dialogsManager.createWidget('confirm', {
				id: 'rb-clear-page-dialog',
				headerMessage: rb.translate('clear_page'),
				message: rb.translate('dialog_confirm_clear_page'),
				position: {
					my: 'center center',
					at: 'center center'
				},
				onConfirm: function() {
					self.getRegion( 'sections' ).currentView.collection.reset();
				}
			} );

			return dialog;
		};
	},

	initLostPageDialog: function() {
		var self = this,
			dialog;

		self.getLostPageDialog = function() {
			if ( dialog ) {
				return dialog;
			}

			dialog = this.dialogsManager.createWidget( 'confirm', {
				id: 'rb-clear-page-dialog',
				headerMessage: rb.translate('changes_lost'),
				message: rb.translate('dialog_confirm_changes_lost'),
				position: {
					my: 'center center',
					at: 'center center'
				},
				onConfirm: function() {
					Backbone.$( '#rb-loading, #rb-preview-loading' ).fadeIn( 600 );
					window.location.href =  self.addUrlParam(window.location.href, 'idLang', id_lang);
				}
			} );

			return dialog;
		};
	},

	initIeEdgeDialog: function() {
		var self = this,
			dialog;

		self.getIeEdgeDialog = function() {
			if ( dialog ) {
				return dialog;
			}

			dialog = this.dialogsManager.createWidget( 'alert', {
				id: 'rb-ie-edge-dialog',
				headerMessage: rb.translate('ie_edge_browser'),
				message: rb.translate('ie_edge_browser_info'),
				position: {
					my: 'center center',
					at: 'center center'
				},
				onConfirm: function() {
					window.location = rb.config.edit_post_link;
				}
			} );

			return dialog;
		};
	},

	clearPage: function() {
		this.getClearPageDialog().show();
	},

	changeLanguage: function(id_lang, ignore) {

		if ( rb.isliveChanged() ) {
			self.id_lang = id_lang;
			this.getLostPageDialog().show();
			return false;
		}
		Backbone.$( '#rb-loading, #rb-preview-loading' ).fadeIn( 600 );
		window.location.href = this.addUrlParam(window.location.href, 'idLang', id_lang);

	},

	detectIE: function() {
		var ua = window.navigator.userAgent;

		var msie = ua.indexOf('MSIE ');
		if (msie > 0) {
			return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
		}

		var trident = ua.indexOf('Trident/');
		if (trident > 0) {
			var rv = ua.indexOf('rv:');
			return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
		}

		var edge = ua.indexOf('Edge/');
		if (edge > 0) {
			return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
		}
		return false;
	},

	addUrlParam: function(url, param, value){
		var a = document.createElement('a'), regex = /(?:\?|&amp;|&)+([^=]+)(?:=([^&]*))*/g;
		var match, str = []; a.href = url; param = encodeURIComponent(param);

		while (match = regex.exec(a.search)){
			if (param != match[1]) str.push(match[1]+(match[2]?"="+match[2]:""));
		}

		str.push(param+(value?"="+ encodeURIComponent(value):""));
		a.search = str.join("&");
		return a.href;
	},



	enqueueTypographyFonts: function() {
		var self = this,
			typographyScheme = this.schemes.getScheme('typography');

		_.each( typographyScheme.items, function( item ) {
			self.helpers.enqueueFont( item.value.font_family );
		} );
	},

	translate: function(stringKey, templateArgs) {
		var string = this.config.dd[stringKey];;

		if (undefined === string) {
			string = stringKey;
		}

		if (templateArgs) {
			string = string.replace( /{(\d+)}/g, function(match, number) {
				return undefined !== templateArgs[number] ? templateArgs[number] : match;
			});
		}

		return string;
	}
} );

module.exports = ( window.rb = new App() ).start();

},{"rb-layouts/panel/panel":51,"rb-models/element":54,"rb-templates/manager":9,"rb-utils/ajax":58,"rb-utils/helpers":59,"rb-utils/introduction":60,"rb-utils/modals":63,"rb-utils/presets-factory":64,"rb-utils/schemes":65,"rb-views/controls/animation":69,"rb-views/controls/autocomplete-products":70,"rb-views/controls/base":73,"rb-views/controls/box-shadow":74,"rb-views/controls/choose":75,"rb-views/controls/color":76,"rb-views/controls/dimensions":77,"rb-views/controls/font":78,"rb-views/controls/gallery":79,"rb-views/controls/icon":80,"rb-views/controls/image-dimensions":81,"rb-views/controls/media":82,"rb-views/controls/repeater":84,"rb-views/controls/section":85,"rb-views/controls/select-sort":86,"rb-views/controls/select2":87,"rb-views/controls/slider":88,"rb-views/controls/structure":89,"rb-views/controls/url":90,"rb-views/controls/wp_widget":91,"rb-views/controls/wysiwyg":92,"rb-views/sections":95}],28:[function(require,module,exports){
var EditModeItemView;

EditModeItemView = Marionette.ItemView.extend( {
	template: '#dd-rb-mode-switcher-content',

	id: 'rb-mode-switcher-inner',

	ui: {
		previewButton: '#rb-mode-switcher-preview-input',
		previewLabel: '#rb-mode-switcher-preview',
		previewLabelA11y: '#rb-mode-switcher-preview .rb-screen-only'
	},

	events: {
		'change @ui.previewButton': 'onEditModeChange'
	},

	getCurrentMode: function() {
		return this.ui.previewButton.is( ':checked' ) ? 'preview' : 'edit';
	},

	setMode: function( mode ) {
		this.ui.previewButton.prop( 'checked', 'preview' === mode );
	},

	onRender: function() {
		this.onEditModeChange();
	},

	onEditModeChange: function() {
		var dataEditMode = rb.channels.dataEditMode,
			oldEditMode = dataEditMode.request( 'activeMode' ),
			currentMode = this.getCurrentMode();

		dataEditMode.reply( 'activeMode', currentMode );

		if ( currentMode !== oldEditMode ) {
			dataEditMode.trigger( 'switch' );

			var title = 'preview' === currentMode ? 'Back to live' : 'Preview';

			this.ui.previewLabel.attr( 'title', title );
			this.ui.previewLabelA11y.text( title );
		}
	}
} );

module.exports = EditModeItemView;

},{}],29:[function(require,module,exports){
var PanelFooterItemView;

PanelFooterItemView = Marionette.ItemView.extend( {
	template: '#dd-rb-panel-footer-content',

	tagName: 'nav',

	id: 'rb-panel-footer-tools',

	possibleRotateModes: [ 'portrait', 'landscape' ],

	ui: {
		menuButtons: '.rb-panel-footer-tool',
		deviceModeIcon: '#rb-panel-footer-responsive > i',
		deviceModeButtons: '#rb-panel-footer-responsive .rb-panel-footer-sub-menu-item',
		buttonSave: '#rb-panel-footer-save',
		buttonSaveButton: '#rb-panel-footer-save .rb-button',
		buttonPublish: '#rb-panel-footer-publish',
		watchTutorial: '#rb-panel-footer-watch-tutorial',
		showTemplates: '#rb-panel-footer-templates-modal',
		saveTemplate: '#rb-panel-footer-save-template',
		buttonGoBackoffice: '#rb-panel-footer-view-edit-page',
	},

	events: {
		'click @ui.deviceModeButtons': 'onClickResponsiveButtons',
		'click @ui.buttonSave': 'onClickButtonSave',
		'click @ui.buttonPublish': 'onClickButtonPublish',
		'click @ui.watchTutorial': 'onClickWatchTutorial',
		'click @ui.showTemplates': 'onClickShowTemplates',
		'click @ui.buttonGoBackoffice': 'onClickButtonGoBackoffice',
		'click @ui.saveTemplate': 'onClickSaveTemplate'
	},

	initialize: function() {
		this._initDialog();

		this.listenTo( rb.channels.live, 'live:changed', this.onliveChanged )
			.listenTo( rb.channels.deviceMode, 'change', this.onDeviceModeChange );
	},

	_initDialog: function() {
		var dialog;

		this.getDialog = function() {
			if ( ! dialog ) {
				var $ = Backbone.$,
					$dialogMessage = $( '<div>', {
						'class': 'rb-dialog-message'
					} ),
					$messageIcon = $( '<i>', {
						'class': 'fa fa-check-circle'
					} ),
					$messageText = $( '<div>', {
						'class': 'rb-dialog-message-text'
					} ).text(rb.translate('saved'));

				$dialogMessage.append( $messageIcon, $messageText );

				dialog = rb.dialogsManager.createWidget( 'popup', {
					hide: {
						delay: 1500
					}
				} );

				dialog.setMessage( $dialogMessage );
			}

			return dialog;
		};
	},

	_publishBuilder: function() {
		var self = this;

		var options = {
			revision: 'publish',
			onSuccess: function() {
				self.getDialog().show();

				self.ui.buttonSaveButton.removeClass( 'rb-button-state' );
			}
		};

		self.ui.buttonSaveButton.addClass( 'rb-button-state' );

		rb.saveBuilder( options );
	},

	_saveBuilderDraft: function() {
		rb.saveBuilder();
	},

	getDeviceModeButton: function( deviceMode ) {
		return this.ui.deviceModeButtons.filter( '[data-device-mode="' + deviceMode + '"]' );
	},

	onPanelClick: function( event ) {
		var $target = Backbone.$( event.target ),
			isClickInsideOfTool = $target.closest( '.rb-panel-footer-sub-menu-wrapper' ).length;

		if ( isClickInsideOfTool ) {
			return;
		}

		var $tool = $target.closest( '.rb-panel-footer-tool' ),
			isClosedTool = $tool.length && ! $tool.hasClass( 'rb-open' );

		this.ui.menuButtons.removeClass( 'rb-open' );

		if ( isClosedTool ) {
			$tool.addClass( 'rb-open' );
		}
	},

	onliveChanged: function() {
		this.ui.buttonSave.toggleClass( 'rb-save-active', rb.isliveChanged() );
	},

	onDeviceModeChange: function() {
		var previousDeviceMode = rb.channels.deviceMode.request( 'previousMode' ),
			currentDeviceMode = rb.channels.deviceMode.request( 'currentMode' );

		this.getDeviceModeButton( previousDeviceMode ).removeClass( 'active' );

		this.getDeviceModeButton( currentDeviceMode ).addClass( 'active' );

		// Change the footer icon
		this.ui.deviceModeIcon.removeClass( 'eicon-device-' + previousDeviceMode ).addClass( 'eicon-device-' + currentDeviceMode );
	},

	onClickButtonSave: function() {
		this._publishBuilder();
	},

	onClickButtonPublish: function( event ) {
		// Prevent click on save button
		event.stopPropagation();

		this._publishBuilder();
	},

	onClickResponsiveButtons: function( event ) {
		var $clickedButton = this.$( event.currentTarget ),
			newDeviceMode = $clickedButton.data( 'device-mode' );

		rb.changeDeviceMode( newDeviceMode );
	},

	onClickWatchTutorial: function() {
		rb.introduction.startIntroduction();
	},

	onClickShowTemplates: function() {
		rb.templates.startModal( function() {
			rb.templates.showTemplates();
		} );
	},

	onClickSaveTemplate: function() {
		rb.templates.startModal( function() {
			rb.templates.getLayout().showSaveTemplateView();
		} );
	},

	onClickButtonGoBackoffice: function(e) {
		e.preventDefault();
		window.location = rb.config.edit_post_link;
	},

	onRender: function() {
		var self = this;

		_.defer( function() {
			rb.getPanelView().$el.on( 'click', _.bind( self.onPanelClick, self ) );
		} );
	}
} );

module.exports = PanelFooterItemView;

},{}],30:[function(require,module,exports){
var PanelHeaderItemView;

PanelHeaderItemView = Marionette.ItemView.extend( {
	template: '#dd-rb-panel-header',

	id: 'rb-panel-header',

	ui: {
		menuButton: '#rb-panel-header-menu-button',
		title: '#rb-panel-header-title',
		addButton: '#rb-panel-header-add-button'
	},

	events: {
		'click @ui.addButton': 'onClickAdd',
		'click @ui.menuButton': 'onClickMenu'
	},

	setTitle: function( title ) {
		this.ui.title.html( title );
	},

	onClickAdd: function() {
		rb.getPanelView().setPage( 'elements' );
	},

	onClickMenu: function() {
		var panel = rb.getPanelView(),
			currentPanelPageName = panel.getCurrentPageName(),
			nextPage = 'menu' === currentPanelPageName ? 'elements' : 'menu';

		panel.setPage( nextPage );
	}
} );

module.exports = PanelHeaderItemView;

},{}],31:[function(require,module,exports){
var liveCompositeView;

liveCompositeView = Marionette.CompositeView.extend( {
	template: Marionette.TemplateCache.get('#dd-live-content'),

	id: 'rb-panel-page-live',

	templateHelpers: function() {
		return {
			elementData: rb.getElementData( this.model )
		};
	},

	childViewContainer: 'div.rb-controls',

	modelEvents: {
		'destroy': 'onModelDestroy'
	},

	ui: {
		'tabs': '.rb-tabs-controls li'
	},

	events: {
		'click @ui.tabs a': 'onClickTabControl'
	},

	initialize: function() {
		this.listenTo( rb.channels.deviceMode, 'change', this.onDeviceModeChange );
	},

	getChildView: function( item ) {
		var controlType = item.get( 'type' );
		return rb.getControlItemView( controlType );
	},

	childViewOptions: function() {
		return {
			elementSettingsModel: this.model.get( 'settings' ),
			elementEditSettings: this.model.get( 'editSettings' )
		};
	},

	onDestroy: function() {
		this.getOption( 'editedElementView' ).$el.removeClass( 'rb-element-editable' );
		this.model.trigger( 'live:close' );

		this.triggerMethod( 'live:destroy' );
	},

	onBeforeRender: function() {
		var controls = rb.getElementControls( this.model.get( 'settings' ) );

		if ( ! controls ) {
			throw new Error( 'live controls not found' );
		}

		// Create new instance of that collection
		this.collection = new Backbone.Collection( controls );
	},

	onRender: function() {
		this.getOption( 'editedElementView' ).$el.addClass( 'rb-element-editable' );

		// Set the first tab as active
		this.ui.tabs.eq( 0 ).find( 'a' ).trigger( 'click' );

		// Create tooltip on controls
		this.$( '.tooltip-target' ).tipsy( {
			gravity: function() {
				// `n` for down, `s` for up
				var gravity = Backbone.$( this ).data( 'tooltip-pos' );

				if ( undefined !== gravity ) {
					return gravity;
				} else {
					return 'n';
				}
			},
			title: function() {
				return this.getAttribute( 'data-tooltip' );
			}
		} );
	},

	onModelDestroy: function() {
		this.destroy();
	},

	onClickTabControl: function( event ) {
		event.preventDefault();

		var $thisTab = this.$( event.target );

		this.ui.tabs.removeClass( 'active' );
		$thisTab.closest( 'li' ).addClass( 'active' );

		this.model.get( 'settings' ).trigger( 'control:switch:tab', $thisTab.data( 'tab' ) );

		this.openFirstSectionInCurrentTab( $thisTab.data( 'tab' ) );
	},

	onDeviceModeChange: function() {
		var self = this;

		self.$el.removeClass( 'rb-responsive-switchers-open' );

		// Timeout according to preview resize css animation duration
		setTimeout( function() {
			rb.$previewContents.find( 'html, body' ).animate( {
				scrollTop: self.getOption( 'editedElementView' ).$el.offset().top - rb.$preview[0].contentWindow.innerHeight / 2
			} );
		}, 500 );
	},

	/**
	 * It's a temp method.
	 *
	 * TODO: Rewrite this method later.
	 */
	openFirstSectionInCurrentTab: function( currentTab ) {
		var openedClass = 'rb-open',

			childrenUnderSection = this.children.filter( function( view ) {
				return ( ! _.isEmpty( view.model.get( 'section' ) ) );
			} ),

			firstSectionControlView = this.children.filter( function( view ) {
				return ( 'section' === view.model.get( 'type' ) ) && ( currentTab === view.model.get( 'tab' ) );
			} );

		// Check if found any section controls
		if ( _.isEmpty( firstSectionControlView ) ) {
			return;
		}

		firstSectionControlView = firstSectionControlView[0];
		firstSectionControlView.ui.heading.addClass( openedClass );

		_.each( childrenUnderSection, function( view ) {
			if ( view.model.get( 'section' ) !== firstSectionControlView.model.get( 'name' ) ) {
				view.$el.removeClass( openedClass );
				return;
			}

			view.$el.addClass( openedClass );
		} );
	},

	onChildviewControlSectionClicked: function( childView ) {
		var openedClass = 'rb-open',
			sectionClicked = childView.model.get( 'name' ),
			isSectionOpen = childView.ui.heading.hasClass( openedClass ),

			childrenUnderSection = this.children.filter( function( view ) {
				return ( ! _.isEmpty( view.model.get( 'section' ) ) );
			} );

		this.$( '.rb-control.rb-control-type-section .rb-panel-heading' ).removeClass( openedClass );

		if ( isSectionOpen ) {
			// Close all open sections
			sectionClicked = '';
		} else {
			childView.ui.heading.addClass( openedClass );
		}

		_.each( childrenUnderSection, function( view ) {
			if ( view.model.get( 'section' ) !== sectionClicked ) {
				view.$el.removeClass( openedClass );
				return;
			}

			view.$el.addClass( openedClass );
		} );

		rb.channels.data.trigger( 'scrollbar:update' );
	}
} );

module.exports = liveCompositeView;

},{}],32:[function(require,module,exports){
var PanelElementsCategory = require( '../models/element' ),
	PanelElementsCategoriesCollection;

PanelElementsCategoriesCollection = Backbone.Collection.extend( {
	model: PanelElementsCategory
} );

module.exports = PanelElementsCategoriesCollection;

},{"../models/element":35}],33:[function(require,module,exports){
var PanelElementsElementModel = require( '../models/element' ),
	PanelElementsElementsCollection;

PanelElementsElementsCollection = Backbone.Collection.extend( {
	model: PanelElementsElementModel/*,
	comparator: 'title'*/
} );

module.exports = PanelElementsElementsCollection;

},{"../models/element":35}],34:[function(require,module,exports){
var PanelElementsCategoriesCollection = require( './collections/categories' ),
	PanelElementsElementsCollection = require( './collections/elements' ),
	PanelElementsCategoriesView = require( './views/categories' ),
	PanelElementsElementsView = require( './views/elements' ),
	PanelElementsSearchView = require( './views/search' ),
	PanelElementsLanguageselectorView = require( './views/languageselector' ),
	PanelElementsLayoutView;

PanelElementsLayoutView = Marionette.LayoutView.extend( {
	template: '#dd-rb-panel-elements',

	regions: {
		elements: '#rb-panel-elements-wrapper',
		search: '#rb-panel-elements-search-area',
		languageselector: '#rb-panel-elements-languageselector-area'
	},

	elementsCollection: null,

	categoriesCollection: null,

	initialize: function() {
		this.listenTo( rb.channels.panelElements, 'element:selected', this.destroy );
	},

	initElementsCollection: function() {
		var elementsCollection = new PanelElementsElementsCollection(),
			sectionConfig = rb.config.elements.section;

		elementsCollection.add( {
			title: rb.translate('inner_section'),
			elType: 'section',
			categories: sectionConfig.categories,
			keywords: sectionConfig.keywords,
			icon: sectionConfig.icon
		} );

		// TODO: Change the array from server syntax, and no need each loop for initialize
		_.each( rb.config.widgets, function( element, widgetType ) {
			elementsCollection.add( {
				title: element.title,
				elType: 'widget',
				categories: element.categories,
				keywords: element.keywords,
				icon: element.icon,
				widgetType: widgetType
			} );
		} );

		this.elementsCollection = elementsCollection;
	},

	initCategoriesCollection: function() {
		var categories = {};

		this.elementsCollection.each( function( element ) {
			_.each( element.get( 'categories' ), function( category ) {
				if ( ! categories[ category ] ) {
					categories[ category ] = [];
				}

				categories[ category ].push( element );
			} );
		} );

		var categoriesCollection = new PanelElementsCategoriesCollection();

		_.each( rb.config.elements_categories, function( categoryConfig, categoryName ) {
			if ( ! categories[ categoryName ] ) {
				return;
			}

			categoriesCollection.add( {
				name: categoryName,
				title: categoryConfig.title,
				icon: categoryConfig.icon,
				items: categories[ categoryName ]
			} );
		} );

		this.categoriesCollection = categoriesCollection;
	},

	showCategoriesView: function() {
		this.getRegion( 'elements' ).show( new PanelElementsCategoriesView( { collection: this.categoriesCollection } ) );
	},

	showElementsView: function() {
		this.getRegion( 'elements' ).show( new PanelElementsElementsView( { collection: this.elementsCollection } ) );
	},

	clearSearchInput: function() {
		this.getChildView( 'search' ).clearInput();
	},

	changeFilter: function( filterValue ) {
		rb.channels.panelElements
			.reply( 'filter:value', filterValue )
			.trigger( 'change' );
	},

	clearFilters: function() {
		this.changeFilter( null );
		this.clearSearchInput();
	},

	onChildviewChildrenRender: function() {
		this.updateElementsScrollbar();
	},

	onChildviewSearchChangeInput: function( child ) {
		var value = child.ui.input.val();

		if ( _.isEmpty( value ) ) {
			this.showCategoriesView();
		} else {
			var oldValue = rb.channels.panelElements.request( 'filter:value' );

			if ( _.isEmpty( oldValue ) ) {
				this.showElementsView();
			}
		}

		this.changeFilter( value, 'search' );
	},

	onDestroy: function() {
		rb.channels.panelElements.reply( 'filter:value', null );
	},

	onShow: function() {
		var searchRegion = this.getRegion( 'search' );
		var languageselectorRegion = this.getRegion( 'languageselector' );

		this.initElementsCollection();
		this.initCategoriesCollection();
		this.showCategoriesView();

		searchRegion.show( new PanelElementsSearchView() );
		languageselectorRegion.show( new PanelElementsLanguageselectorView() );
	},

	updateElementsScrollbar: function() {
		rb.channels.data.trigger( 'scrollbar:update' );
	}
} );

module.exports = PanelElementsLayoutView;

},{"./collections/categories":32,"./collections/elements":33,"./views/categories":36,"./views/elements":39,"./views/languageselector":40,"./views/search":41}],35:[function(require,module,exports){
var PanelElementsElementModel;

PanelElementsElementModel = Backbone.Model.extend( {
	defaults: {
		title: '',
		categories: [],
		keywords: [],
		icon: '',
		elType: 'widget',
		widgetType: ''
	}
} );

module.exports = PanelElementsElementModel;

},{}],36:[function(require,module,exports){
var PanelElementsCategoryView = require( './category' ),
	PanelElementsCategoriesView;

PanelElementsCategoriesView = Marionette.CollectionView.extend( {
	childView: PanelElementsCategoryView,

	id: 'rb-panel-elements-categories'
} );

module.exports = PanelElementsCategoriesView;

},{"./category":37}],37:[function(require,module,exports){
var PanelElementsElementView = require( './element' ),
	PanelElementsElementsCollection = require( '../collections/elements' ),
	PanelElementsCategoryView;

PanelElementsCategoryView = Marionette.CompositeView.extend( {
	template: '#dd-rb-panel-elements-category',

	className: 'rb-panel-category',

	childView: PanelElementsElementView,

	childViewContainer: '.panel-elements-category-items',

	initialize: function() {
		this.collection = new PanelElementsElementsCollection( this.model.get( 'items' ) );
	}
} );

module.exports = PanelElementsCategoryView;

},{"../collections/elements":33,"./element":38}],38:[function(require,module,exports){
var PanelElementsElementView;

PanelElementsElementView = Marionette.ItemView.extend({
	template: '#dd-rb-element-library-element',

	className: 'rb-element-wrapper',

	onRender: function() {
		var self = this;

		this.$el.html5Draggable( {
			onDragStart: function() {
				rb.channels.panelElements
					.reply('element:selected', self)
					.trigger( 'element:drag:start' );
			},

			onDragEnd: function() {
				rb.channels.panelElements.trigger( 'element:drag:end' );
			},

			groups: [ 'rb-element' ]
		} );
	}
});

module.exports = PanelElementsElementView;

},{}],39:[function(require,module,exports){
var PanelElementsElementView = require( './element' ),
	PanelElementsElementsView;

PanelElementsElementsView = Marionette.CollectionView.extend( {
	childView: PanelElementsElementView,

	id: 'rb-panel-elements',

	initialize: function() {
		this.listenTo( rb.channels.panelElements, 'change', this.onFilterChanged );
	},

	filter: function( childModel ) {
		var filterValue = rb.channels.panelElements.request( 'filter:value' );

		if ( ! filterValue ) {
			return true;
		}

		return _.any( [ 'title', 'keywords' ], function( type ) {
			return ( -1 !== childModel.get( type ).toLowerCase().indexOf( filterValue.toLowerCase() ) );
		} );
	},

	onFilterChanged: function() {
		this._renderChildren();
		this.triggerMethod( 'children:render' );
	}
} );

module.exports = PanelElementsElementsView;

},{"./element":38}],40:[function(require,module,exports){
var PanelElementsLanguageselectorView;

PanelElementsLanguageselectorView = Marionette.ItemView.extend( {
	template: '#dd-rb-panel-element-languageselector',

	id: 'rb-panel-elements-languageselector-wrapper',

	ui: {
		select: 'select',
		btnShowLanguages: '#rb-panel-elements-language-import-btn',
		btnLanguageImport: '.rb-panel-elements-language-import-lng'
	},

	events: {
		'change @ui.select': 'onSelectChanged',
		'click @ui.btnShowLanguages': 'onShowLanguagesClick',
		'click @ui.btnLanguageImport': 'onLanguageImportClick',
	},

	initialize: function() {
		this.initImportLanguageDialog();
	},

	onSelectChanged: function( ) {
		if (!rb.changeLanguage($(this.ui.select).val())) {
			$(this.ui.select).val(rb.config.id_lang);
		}
	},

	onShowLanguagesClick: function( ) {
		$(this.ui.btnShowLanguages).parent().toggleClass('rb-open');
	},

	initImportLanguageDialog: function( ) {
		var self = this,
			dialog;


		self.getImportLanguageDialog = function(id_lang) {
			if ( dialog ) {
				return dialog;
			}

			dialog = rb.dialogsManager.createWidget( 'confirm', {
				id: 'rb-import-language-dialog',
				headerMessage: rb.translate('import_language_dialog_title'),
				message: rb.translate('import_language_dialog_msg'),
				position: {
					my: 'center center',
					at: 'center center'
				},
				onConfirm: function() {
					Backbone.$( '#rb-loading, #rb-preview-loading' ).fadeIn( 600 );
					rb.getRegion( 'sections' ).currentView.collection.reset();

					rb.ajax.send( 'getLanguageContent', {
						data: {
							id_lang: id_lang,
							page_type: rb.config.page_type,
							page_id: rb.config.post_id,
						},
						success: function( data ) {
							rb.getRegion( 'sections' ).currentView.addChildModel( data );
							Backbone.$( '#rb-loading, #rb-preview-loading' ).fadeOut( 600 );
						},
					} );
				}
			} );

			return dialog;
		};
	},

	onLanguageImportClick: function( element ) {
		element.preventDefault();
		var id_lang = $(element.currentTarget).data('language');
		this.getImportLanguageDialog(id_lang).show();

	},


} );

module.exports = PanelElementsLanguageselectorView;

},{}],41:[function(require,module,exports){
var PanelElementsSearchView;

PanelElementsSearchView = Marionette.ItemView.extend( {
	template: '#dd-rb-panel-element-search',

	id: 'rb-panel-elements-search-wrapper',

	ui: {
		input: 'input'
	},

	events: {
		'keyup @ui.input': 'onInputChanged'
	},

	onInputChanged: function( event ) {
		var ESC_KEY = 27;

		if ( ESC_KEY === event.keyCode ) {
			this.clearInput();
		}

		this.triggerMethod( 'search:change:input' );
	},

	clearInput: function() {
		this.ui.input.val( '' );
	}
} );

module.exports = PanelElementsSearchView;

},{}],42:[function(require,module,exports){
var PanelMenuItemView = require( 'rb-panel/pages/menu/views/item' ),
	PanelMenuPageView;

PanelMenuPageView = Marionette.CollectionView.extend( {
	id: 'rb-panel-page-menu',

	childView: PanelMenuItemView,

	initialize: function() {
		this.collection = new Backbone.Collection( [
			{
				icon: 'eraser',
				title: rb.translate('clear_page'),
				callback: function() {
					rb.clearPage();
				}
			},
			{
				icon: 'info-circle',
				title: rb.translate('about_rb'),
				type: 'link',
				link: rb.config.rb_site,
				newTab: true
			}
		] );
	},

	onChildviewClick: function( childView ) {
		var menuItemType = childView.model.get( 'type' );

		switch ( menuItemType ) {
			case 'page' :
				var pageName = childView.model.get( 'pageName' ),
					pageTitle = childView.model.get( 'title' );

				rb.getPanelView().setPage( pageName, pageTitle );
				break;

			case 'link' :
				var link = childView.model.get( 'link' ),
					isNewTab = childView.model.get( 'newTab' );

				if ( isNewTab ) {
					open( link, '_blank' );
				} else {
					location.href = childView.model.get( 'link' );
				}

				break;

			default:
				var callback = childView.model.get( 'callback' );

				if ( _.isFunction( callback ) ) {
					callback.call( childView );
				}
		}
	}
} );

module.exports = PanelMenuPageView;

},{"rb-panel/pages/menu/views/item":43}],43:[function(require,module,exports){
var PanelMenuItemView;

PanelMenuItemView = Marionette.ItemView.extend({
	template: '#dd-rb-panel-menu-item',

	className: 'rb-panel-menu-item',

	triggers: {
		click: 'click'
	}
});

// console.log(PanelMenuItemView);

module.exports = PanelMenuItemView;

},{}],44:[function(require,module,exports){
var PanelSchemeBaseView;

PanelSchemeBaseView = Marionette.CompositeView.extend( {
	id: function() {
		return 'rb-panel-scheme-' + this.getType();
	},

	className: 'rb-panel-scheme',

	childViewContainer: '.rb-panel-scheme-items',

	getTemplate: function() {
		return Marionette.TemplateCache.get( '#dd-rb-panel-schemes-' + this.getType() );
	},

	ui: function() {
		return {
			saveButton: '.rb-panel-scheme-save .rb-button',
			discardButton: '.rb-panel-scheme-discard .rb-button',
			resetButton: '.rb-panel-scheme-reset .rb-button'
		};
	},

	events: function() {
		return {
			'click @ui.saveButton': 'saveScheme',
			'click @ui.discardButton': 'discardScheme',
			'click @ui.resetButton': 'setDefaultScheme'
		};
	},

	initialize: function() {
		this.model = new Backbone.Model();

		this.resetScheme();
	},

	getType: function() {},

	getScheme: function() {
		return rb.schemes.getScheme( this.getType() );
	},

	changeChildrenUIValues: function( schemeItems ) {
		var self = this;

		_.each( schemeItems, function( value, key ) {
			var model = self.collection.findWhere( { key: key } ),
				childView = self.children.findByModelCid( model.cid );

			childView.changeUIValue( value );
		} );
	},

	discardScheme: function() {
		rb.schemes.resetSchemes( this.getType() );

		this.ui.saveButton.prop( 'disabled', true );

		this._renderChildren();
	},

	setSchemeValue: function( key, value ) {
		rb.schemes.setSchemeValue( this.getType(), key, value );
	},

	saveScheme: function() {
		rb.schemes.saveScheme( this.getType() );

		this.ui.saveButton.prop( 'disabled', true );

		this.resetScheme();

		this._renderChildren();
	},

	setDefaultScheme: function() {
		var defaultScheme = rb.config.default_schemes[ this.getType() ].items;

		this.changeChildrenUIValues( defaultScheme );
	},

	resetItems: function() {
		this.model.set( 'items', this.getScheme().items );
	},

	resetCollection: function() {
		var items = this.model.get( 'items' );

		this.collection = new Backbone.Collection();

		_.each( items, _.bind( function( item, key ) {
			item.type = this.getType();
			item.key = key;

			this.collection.add( item );
		}, this ) );
	},

	resetScheme: function() {
		this.resetItems();
		this.resetCollection();
	},

	onChildviewValueChange: function( childView, newValue ) {
		this.ui.saveButton.removeProp( 'disabled' );

		this.setSchemeValue( childView.model.get( 'key' ), newValue );
	}
} );

module.exports = PanelSchemeBaseView;

},{}],45:[function(require,module,exports){
var PanelSchemeBaseView = require( 'rb-panel/pages/schemes/base' ),
	PanelSchemeColorsView;

PanelSchemeColorsView = PanelSchemeBaseView.extend( {

	ui: function() {
		var ui = PanelSchemeBaseView.prototype.ui.apply( this, arguments );

		ui.systemSchemes = '.rb-panel-scheme-color-system-scheme';

		return ui;
	},

	events: function() {
		var events = PanelSchemeBaseView.prototype.events.apply( this, arguments );

		events[ 'click @ui.systemSchemes' ] = 'onSystemSchemeClick';

		return events;
	},

	getChildView: function() {
		return require( 'rb-panel/pages/schemes/items/color' );
	},

	getType: function() {
		return 'color';
	},

	onSystemSchemeClick: function( event ) {
		var $schemeClicked = Backbone.$( event.currentTarget ),
			schemeName = $schemeClicked.data( 'schemeName' ),
			scheme = rb.config.system_schemes.color[ schemeName ].items;

		this.changeChildrenUIValues( scheme );
	}
} );

module.exports = PanelSchemeColorsView;

},{"rb-panel/pages/schemes/base":44,"rb-panel/pages/schemes/items/color":48}],46:[function(require,module,exports){
var PanelSchemeDisabledView;

PanelSchemeDisabledView = Marionette.ItemView.extend( {
	template: '#dd-rb-panel-schemes-disabled',

	disabledTitle: '',

	templateHelpers: function() {
		return {
			disabledTitle: this.disabledTitle
		};
	},

	id: 'rb-panel-schemes-disabled'
} );

module.exports = PanelSchemeDisabledView;

},{}],47:[function(require,module,exports){
var PanelSchemeItemView;

PanelSchemeItemView = Marionette.ItemView.extend( {
	getTemplate: function() {
		return Marionette.TemplateCache.get( '#dd-rb-panel-scheme-' + this.model.get( 'type' ) + '-item' );
	},

	className: function() {
		return 'rb-panel-scheme-item';
	}
} );

module.exports = PanelSchemeItemView;

},{}],48:[function(require,module,exports){
var PanelSchemeItemView = require( 'rb-panel/pages/schemes/items/base' ),
	PanelSchemeColorView;

PanelSchemeColorView = PanelSchemeItemView.extend( {
	ui: {
		input: '.rb-panel-scheme-color-value'
	},

	changeUIValue: function( newValue ) {
		this.ui.input.wpColorPicker( 'color', newValue );
	},

	onBeforeDestroy: function() {
		if ( this.ui.input.wpColorPicker( 'instance' ) ) {
			this.ui.input.wpColorPicker( 'close' );
		}
	},

	onRender: function() {
		this.ui.input.wpColorPicker( {
			change: _.bind( function( event, ui ) {
				this.triggerMethod( 'value:change', ui.color.toString() );
			}, this )
		} );
	}
} );

module.exports = PanelSchemeColorView;

},{"rb-panel/pages/schemes/items/base":47}],49:[function(require,module,exports){
var PanelSchemeItemView = require( 'rb-panel/pages/schemes/items/base' ),
	PanelSchemeTypographyView;

PanelSchemeTypographyView = PanelSchemeItemView.extend( {
	className: function() {
		var classes = PanelSchemeItemView.prototype.className.apply( this, arguments );

		return classes + ' rb-panel-box';
	},

	ui: {
		heading: '.rb-panel-heading',
		allFields: '.rb-panel-scheme-typography-item-field',
		inputFields: 'input.rb-panel-scheme-typography-item-field',
		selectFields: 'select.rb-panel-scheme-typography-item-field',
		selectFamilyFields: 'select.rb-panel-scheme-typography-item-field[name="font_family"]'
	},

	events: {
		'input @ui.inputFields': 'onFieldChange',
		'change @ui.selectFields': 'onFieldChange',
		'click @ui.heading': 'toggleVisibility'
	},

	onRender: function() {
		var self = this;

		this.ui.inputFields.add( this.ui.selectFields ).each( function() {
			var $this = Backbone.$( this ),
				name = $this.attr( 'name' ),
				value = self.model.get( 'value' )[ name ];

			$this.val( value );
		} );

		this.ui.selectFamilyFields.select2( {
			dir: rb.config.is_rtl ? 'rtl' : 'ltr'
		} );
	},

	toggleVisibility: function() {
		this.ui.heading.toggleClass( 'rb-open' );
	},

	changeUIValue: function( newValue ) {
		this.ui.allFields.each( function() {
			var $this = Backbone.$( this ),
				thisName = $this.attr( 'name' ),
				newFieldValue = newValue[ thisName ];

			$this.val( newFieldValue ).trigger( 'change' );
		} );
	},

	onFieldChange: function( event ) {
		var $select = this.$( event.currentTarget ),
			currentValue = rb.helpers.cloneObject( this.model.get( 'value' ) ),
			fieldKey = $select.attr( 'name' );

		currentValue[ fieldKey ] = $select.val();

		if ( 'font_family' === fieldKey && ! _.isEmpty( currentValue[ fieldKey ] ) ) {
			rb.helpers.enqueueFont( currentValue[ fieldKey ] );
		}

		this.triggerMethod( 'value:change', currentValue );
	}
} );

module.exports = PanelSchemeTypographyView;

},{"rb-panel/pages/schemes/items/base":47}],50:[function(require,module,exports){
var PanelSchemeBaseView = require( 'rb-panel/pages/schemes/base' ),
	PanelSchemeTypographyView;

PanelSchemeTypographyView = PanelSchemeBaseView.extend( {

	getChildView: function() {
		return require( 'rb-panel/pages/schemes/items/typography' );
	},

	getType: function() {
		return 'typography';
	}
} );

module.exports = PanelSchemeTypographyView;

},{"rb-panel/pages/schemes/base":44,"rb-panel/pages/schemes/items/typography":49}],51:[function(require,module,exports){
var EditModeItemView = require( 'rb-layouts/edit-mode' ),
	PanelLayoutView;

PanelLayoutView = Marionette.LayoutView.extend( {
	template: '#dd-rb-panel',

	id: 'rb-panel-inner',

	regions: {
		content: '#rb-panel-content-wrapper',
		header: '#rb-panel-header-wrapper',
		footer: '#rb-panel-footer',
		modeSwitcher: '#rb-mode-switcher'
	},

	pages: {},

	childEvents: {
		'click:add': function() {
			this.setPage( 'elements' );
		},
		'live:destroy': function() {
			this.setPage( 'elements' );
		}
	},

	currentPageName: null,

	_isScrollbarInitialized: false,

	initialize: function() {
		this.initPages();
	},

	initPages: function() {
		var pages = {
			elements: {
				view: require( 'rb-panel/pages/elements/elements' ),
				title: '<img src="' + rb.config.assets_url + 'img/logo.svg">'
			},
			live: {
				view: require( 'rb-panel/pages/live' )
			},
			menu: {
				view: require( 'rb-panel/pages/menu/menu' ),
				title: '<img src="' + rb.config.assets_url + 'img/logo.svg">'
			},
			colorScheme: {
				view: require( 'rb-panel/pages/schemes/colors' )
			},
			typographyScheme: {
				view: require( 'rb-panel/pages/schemes/typography' )
			}
		};

		var schemesTypes = Object.keys( rb.schemes.getSchemes() ),
			disabledSchemes = _.difference( schemesTypes, rb.schemes.getEnabledSchemesTypes() );

		_.each( disabledSchemes, function( schemeType ) {
			var scheme  = rb.schemes.getScheme( schemeType );

			pages[ schemeType + 'Scheme' ].view = require( 'rb-panel/pages/schemes/disabled' ).extend( {
				disabledTitle: scheme.disabled_title
			} );
		} );

		this.pages = pages;
	},

	getHeaderView: function() {
		return this.getChildView( 'header' );
	},

	getCurrentPageName: function() {
		return this.currentPageName;
	},

	getCurrentPageView: function() {
		return this.getChildView( 'content' );
	},

	setPage: function( page, title, viewOptions ) {
		var pageData = this.pages[ page ];

		if ( ! pageData ) {
			throw new ReferenceError( 'rb panel doesn\'t have page named \'' + page + '\'' );
		}

		this.showChildView( 'content', new pageData.view( viewOptions ) );

		this.getHeaderView().setTitle( title || pageData.title );

		this.currentPageName = page;
	},

	onBeforeShow: function() {
		var PanelFooterItemView = require( 'rb-layouts/panel/footer' ),
			PanelHeaderItemView = require( 'rb-layouts/panel/header' );

		// Edit Mode
		this.showChildView( 'modeSwitcher', new EditModeItemView() );

		// Header
		this.showChildView( 'header', new PanelHeaderItemView() );

		// Footer
		this.showChildView( 'footer', new PanelFooterItemView() );

		// Added live events
		this.updateScrollbar = _.throttle( this.updateScrollbar, 100 );

		this.getRegion( 'content' )
			.on( 'before:show', _.bind( this.onliveBeforeShow, this ) )
			.on( 'empty', _.bind( this.onliveEmpty, this ) )
			.on( 'show', _.bind( this.updateScrollbar, this ) );

		// Set default page to elements
		this.setPage( 'elements' );

		this.listenTo( rb.channels.data, 'scrollbar:update', this.updateScrollbar );
	},

	onliveBeforeShow: function() {
		_.defer( _.bind( this.updateScrollbar, this ) );
	},

	onliveEmpty: function() {
		this.updateScrollbar();
	},

	updateScrollbar: function() {
		var $panel = this.content.$el;

		if ( ! this._isScrollbarInitialized ) {
			$panel.perfectScrollbar();
			this._isScrollbarInitialized = true;

			return;
		}

		$panel.perfectScrollbar( 'update' );
	}
} );

module.exports = PanelLayoutView;

},{"rb-layouts/edit-mode":28,"rb-layouts/panel/footer":29,"rb-layouts/panel/header":30,"rb-panel/pages/live":31,"rb-panel/pages/elements/elements":34,"rb-panel/pages/menu/menu":42,"rb-panel/pages/schemes/colors":45,"rb-panel/pages/schemes/disabled":46,"rb-panel/pages/schemes/typography":50}],52:[function(require,module,exports){
var BaseSettingsModel;

BaseSettingsModel = Backbone.Model.extend( {

	initialize: function( data ) {
		this.controls = rb.getElementControls( this );
		if ( ! this.controls ) {
			return;
		}

		var attrs = data || {},
			defaults = {};

		_.each( this.controls, function( field ) {
			var control = rb.config.controls[ field.type ];

			if ( _.isObject( control.default_value )  ) {
				defaults[ field.name ] = _.extend( {}, control.default_value, field['default'] || {} );
			} else {
				defaults[ field.name ] = field['default'] || control.default_value;
			}
		} );

		this.defaults = defaults;

		// TODO: Change method to recursive
		attrs = _.defaults( {}, attrs, defaults );

		_.each( this.controls, function( field ) {
			if ( 'repeater' === field.type ) {
				attrs[ field.name ] = new Backbone.Collection( attrs[ field.name ], {
					model: BaseSettingsModel
				} );
			}
		} );

		this.set( attrs );
	},

	getFontControls: function() {
		return _.filter( this.controls, _.bind( function( control ) {
			return 'font' === control.type;
		}, this ) );
	},

	getStyleControls: function( controls ) {
		var self = this;

		controls = controls || self.controls;

		return _.filter( controls, function( control ) {
			if ( control.fields ) {
				control.styleFields = self.getStyleControls( control.fields );

				return true;
			}

			return self.isStyleControl( control.name, controls );
		} );
	},

	isStyleControl: function( attribute, controls ) {
		controls = controls || this.controls;

		var currentControl = _.find( controls, function( control ) {
			return attribute === control.name;
		} );

		return currentControl && ! _.isEmpty( currentControl.selectors );
	},

	getClassControls: function() {
		return _.filter( this.controls, _.bind( function( control ) {
			return this.isClassControl( control.name );
		}, this ) );
	},

	isClassControl: function( attribute ) {
		var currentControl = _.find( this.controls, function( control ) {
			return attribute === control.name;
		} );

		return currentControl && ! _.isUndefined( currentControl.prefix_class );
	},

	getControl: function( id ) {
		return _.find( this.controls, function( control ) {
			return id === control.name;
		} );
	},

	clone: function() {
		return new BaseSettingsModel( rb.helpers.cloneObject( this.attributes ) );
	},

	toJSON: function() {
		var data = Backbone.Model.prototype.toJSON.call( this );

		delete data.widgetType;
		delete data.elType;
		delete data.isInner;

		_.each( data, function( attribute, key ) {
			if ( attribute && attribute.toJSON ) {
				data[ key ] = attribute.toJSON();
			}
		} );

		return data;
	}
} );

module.exports = BaseSettingsModel;
},{}],53:[function(require,module,exports){
var BaseSettingsModel = require( 'rb-models/base-settings' ),
	ColumnSettingsModel;

ColumnSettingsModel = BaseSettingsModel.extend( {
	defaults: {
		_inline_size: '',
		_column_size: 100
	}
} );

module.exports = ColumnSettingsModel;

},{"rb-models/base-settings":52}],54:[function(require,module,exports){
var BaseSettingsModel = require( 'rb-models/base-settings' ),
	WidgetSettingsModel = require( 'rb-models/widget-settings' ),
	ColumnSettingsModel = require( 'rb-models/column-settings' ),
	RowSettingsModel = require( 'rb-models/row-settings' ),
	SectionSettingsModel = require( 'rb-models/section-settings' ),

	ElementModel,
	ElementCollection;

ElementModel = Backbone.Model.extend( {
	defaults: {
		id: '',
		elType: '',
		isInner: false,
		settings: {},
		defaultEditSettings: {}
	},

	remoteRender: false,
	_htmlCache: null,
	_jqueryXhr: null,
	renderOnLeave: false,

	initialize: function( options ) {
		var elements = this.get( 'elements' ),
			elType = this.get( 'elType' ),
			settings;

		var settingModels = {
			widget: WidgetSettingsModel,
			column: ColumnSettingsModel,
			row: RowSettingsModel,
			section: SectionSettingsModel
		};

		var SettingsModel = settingModels[ elType ] || BaseSettingsModel;

		settings = this.get( 'settings' ) || {};
		if ( 'widget' === elType ) {
			settings.widgetType = this.get( 'widgetType' );
		}

		settings.elType = elType;
		settings.isInner = this.get( 'isInner' );

		settings = new SettingsModel( settings );
		this.set( 'settings', settings );

		this.initEditSettings();

		if ( undefined !== elements ) {
			this.set( 'elements', new ElementCollection( elements ) );
		}

		if ( 'widget' === this.get( 'elType' ) ) {
			this.remoteRender = true;
			this.setHtmlCache( options.htmlCache || '' );
		}

		// No need this variable anymore
		delete options.htmlCache;

		// Make call to remote server as throttle function
		this.renderRemoteServer = _.throttle( this.renderRemoteServer, 1000 );

		this.on( 'destroy', this.onDestroy );
		this.on( 'live:close', this.onCloselive );
	},

	initEditSettings: function() {
		this.set( 'editSettings', new Backbone.Model( this.get( 'defaultEditSettings' ) ) );
	},

	onDestroy: function() {
		// Clean the memory for all use instances
		var settings = this.get( 'settings' ),
			elements = this.get( 'elements' );

		if ( undefined !== elements ) {
			_.each( _.clone( elements.models ), function( model ) {
				model.destroy();
			} );
		}
		settings.destroy();
	},

	onCloselive: function() {
		this.initEditSettings();

		if ( this.renderOnLeave ) {
			this.renderRemoteServer();
		}
	},

	setSetting: function( key, value, triggerChange ) {
		triggerChange = triggerChange || false;

		var settings = this.get( 'settings' );

		settings.set( key, value );

		this.set( 'settings', settings );

		if ( triggerChange ) {
			this.trigger( 'change', this );
			this.trigger( 'change:settings', this );
			this.trigger( 'change:settings:' + key, this );
		}
	},

	getSetting: function( key ) {
		var settings = this.get( 'settings' );

		if ( undefined === settings.get( key ) ) {
			return '';
		}

		return settings.get( key );
	},

	setHtmlCache: function( htmlCache ) {
		this._htmlCache = htmlCache;
	},

	getHtmlCache: function() {
		return this._htmlCache;
	},

	getTitle: function() {
		var elementData = rb.getElementData( this );

		return ( elementData ) ? elementData.title : 'Unknown';
	},

	onHtml: function(text)
	{
		return '<h5 class="rb-title-widget">'+text+'<h5>';
	},

	getIcon: function() {
		var elementData = rb.getElementData( this );

		return ( elementData ) ? elementData.icon : 'unknown';
	},

	renderRemoteServer: function() {
		if ( ! this.remoteRender ) {
			return;
		}

		this.renderOnLeave = false;

		this.trigger( 'before:remote:render' );

		if ( this._jqueryXhr && 4 !== this._jqueryXhr ) {
			this._jqueryXhr.abort();
		}

		var data = this.toJSON();

		this._jqueryXhr = rb.ajax.send('renderWidget', {
			data: {
				post_id: rb.config.post_id,
				data: JSON.stringify( data ),
			},
			success: _.bind( this.onRemoteGetHtml, this )
		});
	},

	onRemoteGetHtml: function( data ) {
		this.setHtmlCache( data.render );
		this.trigger( 'remote:render' );
	},

	clone: function() {
		var newModel = Backbone.Model.prototype.clone.apply( this, arguments );
		newModel.set( 'id', rb.helpers.getUniqueID() );

		newModel.setHtmlCache( this.getHtmlCache() );

		var elements = this.get( 'elements' ),
			settings = this.get( 'settings' );

		if ( ! _.isEmpty( elements ) ) {
			newModel.set( 'elements', elements.clone() );
		}

		newModel.set( 'settings', settings.clone() );

		return newModel;
	},

	toJSON: function( options ) {
		options = _.extend( { copyHtmlCache: false }, options );

		// Call parent's toJSON method
		var data = Backbone.Model.prototype.toJSON.call( this );

		_.each( data, function( attribute, key ) {
			if ( attribute && attribute.toJSON ) {
				data[ key ] = attribute.toJSON( options );
			}
		} );

		if ( options.copyHtmlCache ) {
			data.htmlCache = this.getHtmlCache();
		} else {
			delete data.htmlCache;
		}

		return data;
	}

} );

ElementCollection = Backbone.Collection.extend( {
	add: function( models, options, isCorrectSet ) {
		if ( ( ! options || ! options.silent ) && ! isCorrectSet ) {
			throw 'Call Error: Adding model to element collection is allowed only by the dedicated addChildModel() method.';
		}

		return Backbone.Collection.prototype.add.call( this, models, options );
	},

	model: function( attrs, options ) {
		if ( attrs.elType ) {
			return new ElementModel( attrs, options );
		}
		return new Backbone.Model( attrs, options );
	},

	clone: function() {
		var tempCollection = Backbone.Collection.prototype.clone.apply( this, arguments ),
			newCollection = new ElementCollection();

		tempCollection.forEach( function( model ) {
			newCollection.add( model.clone(), null, true );
		} );

		return newCollection;
	}
} );

ElementCollection.prototype.sync = function() {
	return null;
};
ElementCollection.prototype.fetch = function() {
	return null;
};
ElementCollection.prototype.save = function() {
	return null;
};

ElementModel.prototype.sync = function() {
	return null;
};
ElementModel.prototype.fetch = function() {
	return null;
};
ElementModel.prototype.save = function() {
	return null;
};

module.exports = {
	Model: ElementModel,
	Collection: ElementCollection
};

},{"rb-models/base-settings":52,"rb-models/column-settings":53,"rb-models/row-settings":55,"rb-models/section-settings":56,"rb-models/widget-settings":57}],55:[function(require,module,exports){
var BaseSettingsModel = require( 'rb-models/base-settings' ),
	RowSettingsModel;

RowSettingsModel = BaseSettingsModel.extend( {
	defaults: {}
} );

module.exports = RowSettingsModel;

},{"rb-models/base-settings":52}],56:[function(require,module,exports){
var BaseSettingsModel = require( 'rb-models/base-settings' ),
	SectionSettingsModel;

SectionSettingsModel = BaseSettingsModel.extend( {
	defaults: {}
} );

module.exports = SectionSettingsModel;

},{"rb-models/base-settings":52}],57:[function(require,module,exports){
var BaseSettingsModel = require( 'rb-models/base-settings' ),
	WidgetSettingsModel;

WidgetSettingsModel = BaseSettingsModel.extend( {

} );

module.exports = WidgetSettingsModel;

},{"rb-models/base-settings":52}],58:[function(require,module,exports){
var Ajax;

Ajax = {
	config: {},

	initConfig: function() {
		this.config = {
			ajaxParams: {
				type: 'POST',
				url: rb.config.ajaxurl,
				data: {}
			}
		};
	},

	init: function() {
		this.initConfig();
	},

	send: function( action, options ) {
		var ajaxParams = rb.helpers.cloneObject( this.config.ajaxParams );

		options = options || {};

		Backbone.$.extend( ajaxParams, options );

		if ( ajaxParams.data instanceof FormData ) {
			ajaxParams.data.append( 'action', action );
		} else {
			ajaxParams.data.action = action;
		}

		var successCallback = ajaxParams.success,
			errorCallback = ajaxParams.error;

		if ( successCallback || errorCallback ) {
			ajaxParams.success = function( response ) {
				if ( response.success && successCallback ) {
					successCallback( response.data );
				}

				if ( ( ! response.success ) && errorCallback ) {
					errorCallback( response.data );
				}
			};

			if ( errorCallback ) {
				ajaxParams.error = function( data ) {
					errorCallback( data );
				};
			}
		}

		return Backbone.$.ajax( ajaxParams );
	}
};

module.exports = Ajax;

},{}],59:[function(require,module,exports){
var helpers;

helpers = {
	_enqueuedFonts: [],

	elementsHierarchy: {
		section: {
			column: {
				widget: null,
				section: null
			}
		}
	},

	enqueueFont: function( font ) {
		if ( -1 !== this._enqueuedFonts.indexOf( font ) ) {
			return;
		}

		var fontType = rb.config.controls.font.fonts[ font ],
			fontUrl;

		switch ( fontType ) {
			case 'googlefonts' :
				fontUrl = 'https://fonts.googleapis.com/css?family=' + font + ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
				break;

			case 'earlyaccess' :
				var fontLowerString = font.replace( /\s+/g, '' ).toLowerCase();
				fontUrl = 'https://fonts.googleapis.com/earlyaccess/' + fontLowerString + '.css';
				break;
		}

		if ( ! _.isEmpty( fontUrl ) ) {
			rb.$previewContents.find( 'link:last' ).after( '<link href="' + fontUrl + '" rel="stylesheet" type="text/css">' );
		}
		this._enqueuedFonts.push( font );
	},

	getElementChildType: function( elementType, container ) {
		if ( ! container ) {
			container = this.elementsHierarchy;
		}

		if ( undefined !== container[ elementType ] ) {

			if ( Backbone.$.isPlainObject( container[ elementType ] ) ) {
				return Object.keys( container[ elementType ] );
			}

			return null;
		}

		for ( var type in container ) {

			if ( ! container.hasOwnProperty( type ) ) {
				continue;
			}

			if ( ! Backbone.$.isPlainObject( container[ type ] ) ) {
				continue;
			}

			var result = this.getElementChildType( elementType, container[ type ] );

			if ( result ) {
				return result;
			}
		}

		return null;
	},

	getUniqueID: function() {
		var id;

		// TODO: Check conflict models
		//while ( true ) {
			id = Math.random().toString( 36 ).substr( 2, 7 );
			//if ( 1 > $( 'li.item-id-' + id ).length ) {
				return id;
			//}
		//}
	},

	stringReplaceAll: function( string, replaces ) {
		var re = new RegExp( Object.keys( replaces ).join( '|' ), 'gi' );

		return string.replace( re, function( matched ) {
			return replaces[ matched ];
		} );
	},

	isControlVisible: function( controlModel, elementSettingsModel ) {
		var condition;

		// TODO: Better way to get this?
		if ( _.isFunction( controlModel.get ) ) {
			condition = controlModel.get( 'condition' );
		} else {
			condition = controlModel.condition;
		}

		if ( _.isEmpty( condition ) ) {
			return true;
		}

		var hasFields = _.filter( condition, function( conditionValue, conditionName ) {
			var conditionNameParts = conditionName.match( /([a-z_0-9]+)(?:\[([a-z_]+)])?(!?)$/i ),
				conditionRealName = conditionNameParts[1],
				conditionSubKey = conditionNameParts[2],
				isNegativeCondition = !! conditionNameParts[3],
				controlValue = elementSettingsModel.get( conditionRealName );

			if ( conditionSubKey ) {
				controlValue = controlValue[ conditionSubKey ];
			}

			var isContains = ( _.isArray( conditionValue ) ) ? _.contains( conditionValue, controlValue ) : conditionValue === controlValue;

			return isNegativeCondition ? isContains : ! isContains;
		} );

		return _.isEmpty( hasFields );
	},

	cloneObject: function( object ) {
		return JSON.parse( JSON.stringify( object ) );
	},

	getYoutubeIDFromURL: function( url ) {
		var videoIDParts = url.match( /^.*(?:youtu.be\/|v\/|e\/|u\/\w+\/|embed\/|v=)([^#\&\?]*).*/ );

		return videoIDParts && videoIDParts[1];
	},

	disableElementEvents: function( $element ) {
		$element.each( function() {
			var currentPointerEvents = this.style.pointerEvents;

			if ( 'none' === currentPointerEvents ) {
				return;
			}

			Backbone.$( this )
				.data( 'backup-pointer-events', currentPointerEvents )
				.css( 'pointer-events', 'none' );
		} );
	},

	enableElementEvents: function( $element ) {
		$element.each( function() {
			var $this = Backbone.$( this ),
				backupPointerEvents = $this.data( 'backup-pointer-events' );

			if ( undefined === backupPointerEvents ) {
				return;
			}

			$this
				.removeData( 'backup-pointer-events' )
				.css( 'pointer-events', backupPointerEvents );
		} );
	}
};

module.exports = helpers;

},{}],60:[function(require,module,exports){
var Introduction;

Introduction = function() {
	var modal;

	var initModal = function() {
		modal = rb.dialogsManager.createWidget( 'rb-modal', {
			id: 'rb-introduction'
		} );

		modal.on( 'hide', function() {
			modal.getElements( 'message' ).empty(); // In order to stop the video
		} );
	};

	this.getSettings = function() {
		return rb.config.introduction;
	};

	this.getModal = function() {
		if ( ! modal ) {
			initModal();
		}

		return modal;
	};

	this.startIntroduction = function() {
		var settings = this.getSettings();

		this.getModal()
		    .setHeaderMessage( settings.title )
		    .setMessage( settings.content )
		    .show();
	};

	this.startOnLoadIntroduction = function() {
		var settings = this.getSettings();

		if ( ! settings.is_user_should_view ) {
			return;
		}

		setTimeout( _.bind( function() {
			this.startIntroduction();
		}, this ), settings.delay );
	};

};

module.exports = new Introduction();

},{}],61:[function(require,module,exports){
/**
 * HTML5 - Drag and Drop
 */
;(function( $ ) {

	var hasFullDataTransferSupport = function( event ) {
		try {
			event.originalEvent.dataTransfer.setData( 'test', 'test' );

			event.originalEvent.dataTransfer.clearData( 'test' );

			return true;
		} catch ( e ) {
			return false;
		}
	};

	var Draggable = function(userSettings) {
		var self = this,
		settings = {},
		elementsCache = {},
		defaultSettings = {
			element: '',
			groups: null,
			onDragStart: null,
			onDragEnd: null
		};

		var initSettings = function() {
			$.extend( true, settings, defaultSettings, userSettings );
		};

		var initElementsCache = function() {
			elementsCache.$element = $(settings.element);
		};

		var buildElements = function() {
			elementsCache.$element.attr( 'draggable', true );
		};

		var onDragEnd = function(event) {
			if ($.isFunction(settings.onDragEnd)) {
				settings.onDragEnd.call(elementsCache.$element, event, self);
			}
		};

		var onDragStart = function( event ) {
			var groups = settings.groups || [],
				dataContainer = {
					groups: groups
				};

			if ( hasFullDataTransferSupport( event ) ) {
				event.originalEvent.dataTransfer.setData( JSON.stringify( dataContainer ), true );
			}

			if ( $.isFunction( settings.onDragStart ) ) {
				settings.onDragStart.call( elementsCache.$element, event, self );
			}
		};

		var attachEvents = function() {
			elementsCache.$element
				.on( 'dragstart', onDragStart )
				.on( 'dragend', onDragEnd );
		};

		var init = function() {
			initSettings();

			initElementsCache();

			buildElements();

			attachEvents();
		};

		this.destroy = function() {
			elementsCache.$element.off( 'dragstart', onDragStart );

			elementsCache.$element.removeAttr( 'draggable' );
		};

		init();
	};

	var Droppable = function( userSettings ) {
		var self = this,
			settings = {},
			elementsCache = {},
			defaultSettings = {
				element: '',
				items: '>',
				horizontalSensitivity: '10%',
				axis: [ 'vertical', 'horizontal' ],
				groups: null,
				isDroppingAllowed: null,
				onDragEnter: null,
				onDragging: null,
				onDropping: null,
				onDragLeave: null
			};

		var initSettings = function() {
			$.extend( settings, defaultSettings, userSettings );
		};

		var initElementsCache = function() {
			elementsCache.$element = $( settings.element );
		};

		var hasHorizontalDetection = function() {
			return -1 !== settings.axis.indexOf( 'horizontal' );
		};

		var hasVerticalDetection = function() {
			return -1 !== settings.axis.indexOf( 'vertical' );
		};

		var checkHorizontal = function( offsetX, elementWidth ) {
			var isPercentValue,
				sensitivity;

			if ( ! hasHorizontalDetection() ) {
				return false;
			}

			if ( ! hasVerticalDetection() ) {
				return offsetX > elementWidth / 2 ? 'right' : 'left';
			}

			sensitivity = settings.horizontalSensitivity.match( /\d+/ );

			if ( ! sensitivity ) {
				return false;
			}

			sensitivity = sensitivity[ 0 ];

			isPercentValue = /%$/.test( settings.horizontalSensitivity );

			if ( isPercentValue ) {
				sensitivity = elementWidth / sensitivity;
			}

			if ( offsetX > elementWidth - sensitivity ) {
				return 'right';
			} else if ( offsetX < sensitivity ) {
				return 'left';
			}

			return false;
		};

		var getSide = function( element, event ) {
			var $element,
				thisHeight,
				thisWidth,
				side;

			event = event.originalEvent;

			$element = $( element );
			thisHeight = $element.outerHeight();
			thisWidth = $element.outerWidth();

			if ( side = checkHorizontal( event.offsetX, thisWidth ) ) {
				return side;
			}

			if ( ! hasVerticalDetection() ) {
				return false;
			}

			if ( event.offsetY > thisHeight / 2 ) {
				side = 'bottom';
			} else {
				side = 'top';
			}

			return side;
		};

		var isDroppingAllowed = function( element, side, event ) {
			var dataTransferTypes,
				draggableGroups,
				isGroupMatch,
				isDroppingAllowed;

			if ( settings.groups && hasFullDataTransferSupport( event ) ) {

				dataTransferTypes = event.originalEvent.dataTransfer.types;
				isGroupMatch = false;

				dataTransferTypes = Array.prototype.slice.apply( dataTransferTypes ); // Convert to array, since Firefox hold him as DOMStringList

				dataTransferTypes.forEach( function( type ) {
					try {
						draggableGroups = JSON.parse( type );

						if ( ! draggableGroups.groups.slice ) {
							return;
						}

						settings.groups.forEach( function( groupName ) {

							if ( -1 !== draggableGroups.groups.indexOf( groupName ) ) {
								isGroupMatch = true;
								return false; // stops the forEach from extra loops
							}
						} );
					} catch ( e ) {
					}
				} );

				if ( ! isGroupMatch ) {
					return false;
				}
			}

			if ( $.isFunction( settings.isDroppingAllowed ) ) {

				isDroppingAllowed = settings.isDroppingAllowed.call( element, side, event, self );

				if ( ! isDroppingAllowed ) {
					return false;
				}
			}

			return true;
		};

		var onDragEnter = function( event ) {
			if ( event.target !== this ) {
				return;
			}

			// Avoid internal elements event firing
			$( this ).children().each( function() {
				var currentPointerEvents = this.style.pointerEvents;

				if ( 'none' === currentPointerEvents ) {
					return;
				}

				$( this )
					.data( 'backup-pointer-events', currentPointerEvents )
					.css( 'pointer-events', 'none' );
			} );

			var side = getSide( this, event );

			if ( ! isDroppingAllowed( this, side, event ) ) {
				return;
			}

			if ( $.isFunction( settings.onDragEnter ) ) {
				settings.onDragEnter.call( this, side, event, self );
			}
		};

		var onDragOver = function( event ) {
			var side = getSide( this, event );

			if ( ! isDroppingAllowed( this, side, event ) ) {
				return;
			}

			event.preventDefault();

			if ( $.isFunction( settings.onDragging ) ) {
				settings.onDragging.call( this, side, event, self );
			}
		};

		var onDrop = function( event ) {
			var side = getSide( this, event );

			if ( ! isDroppingAllowed( this, side, event ) ) {
				return;
			}

			event.preventDefault();

			if ( $.isFunction( settings.onDropping ) ) {
				settings.onDropping.call( this, side, event, self );
			}
		};

		var onDragLeave = function( event ) {
			// Avoid internal elements event firing
			$( this ).children().each( function() {
				var $this = $( this ),
					backupPointerEvents = $this.data( 'backup-pointer-events' );

				if ( undefined === backupPointerEvents ) {
					return;
				}

				$this
					.removeData( 'backup-pointer-events' )
					.css( 'pointer-events', backupPointerEvents );
			} );

			if ( $.isFunction( settings.onDragLeave ) ) {
				settings.onDragLeave.call( this, event, self );
			}
		};

		var attachEvents = function() {
			elementsCache.$element
				.on( 'dragenter', settings.items, onDragEnter )
				.on( 'dragover', settings.items, onDragOver )
				.on( 'drop', settings.items, onDrop )
				.on( 'dragleave drop', settings.items, onDragLeave );
		};

		var init = function() {
			initSettings();

			initElementsCache();

			attachEvents();
		};

		this.destroy = function() {
			elementsCache.$element
				.off( 'dragenter', settings.items, onDragEnter )
				.off( 'dragover', settings.items, onDragOver )
				.off( 'drop', settings.items, onDrop )
				.off( 'dragleave drop', settings.items, onDragLeave );
		};

		init();
	};

	var plugins = {
		html5Draggable: Draggable,
		html5Droppable: Droppable
	};

	$.each( plugins, function( pluginName, Plugin ) {
		$.fn[ pluginName ] = function( options ) {
			options = options || {};

			this.each( function() {
				var instance = $.data( this, pluginName ),
					hasInstance = instance instanceof Plugin;

				if ( hasInstance ) {

					if ( 'destroy' === options ) {

						instance.destroy();

						$.removeData( this, pluginName );
					}

					return;
				}

				options.element = this;

				$.data( this, pluginName, new Plugin( options ) );
			} );

			return this;
		};
	} );
})( jQuery );

},{}],62:[function(require,module,exports){
/*!
 * jQuery Serialize Object v1.0.1
 */
(function( $ ) {
	$.fn.rbSerializeObject = function() {
		var serializedArray = this.serializeArray(),
			data = {};

		var parseObject = function( dataContainer, key, value ) {
			var isArrayKey = /^[^\[\]]+\[]/.test( key ),
				isObjectKey = /^[^\[\]]+\[[^\[\]]+]/.test( key ),
				keyName = key.replace( /\[.*/, '' );

			if ( isArrayKey ) {
				if ( ! dataContainer[ keyName ] ) {
					dataContainer[ keyName ] = [];
				}
			} else {
				if ( ! isObjectKey ) {
					if ( dataContainer.push ) {
						dataContainer.push( value );
					} else {
						dataContainer[ keyName ] = value;
					}

					return;
				}

				if ( ! dataContainer[ keyName ] ) {
					dataContainer[ keyName ] = {};
				}
			}

			var nextKeys = key.match( /\[[^\[\]]*]/g );

			nextKeys[ 0 ] = nextKeys[ 0 ].replace( /\[|]/g, '' );

			return parseObject( dataContainer[ keyName ], nextKeys.join( '' ), value );
		};

		$.each( serializedArray, function() {
			parseObject( data, this.name, this.value );
		} );
		return data;
	};
})( jQuery );

},{}],63:[function(require,module,exports){
var Modals;

Modals = {
	init: function() {
		this.initModalWidgetType();
	},

	initModalWidgetType: function() {
		var modalProperties = {
			getDefaultSettings: function() {
				var settings = DialogsManager.getWidgetType( 'options' ).prototype.getDefaultSettings.apply( this, arguments );

				return _.extend( settings, {
					position: {
						my: 'center',
						at: 'center'
					},
					contentWidth: 'auto',
					contentHeight: 'auto',
					closeButton: true
				} );
			},
			buildWidget: function() {
				DialogsManager.getWidgetType( 'options' ).prototype.buildWidget.apply( this, arguments );

				if ( ! this.getSettings( 'closeButton' ) ) {
					return;
				}

				var $closeButton = this.addElement( 'closeButton', '<div><i class="fa fa-times"></i></div>' );

				this.getElements( 'widgetContent' ).prepend( $closeButton );
			},
			attachEvents: function() {
				if ( this.getSettings( 'closeButton' ) ) {
					this.getElements( 'closeButton' ).on( 'click', this.hide );
				}
			},
			onReady: function() {
				DialogsManager.getWidgetType( 'options' ).prototype.onReady.apply( this, arguments );

				var elements = this.getElements(),
					settings = this.getSettings();

				if ( 'auto' !== settings.contentWidth ) {
					elements.message.width( settings.contentWidth );
				}

				if ( 'auto' !== settings.contentHeight ) {
					elements.message.height( settings.contentHeight );
				}
			}
		};

		DialogsManager.addWidgetType( 'rb-modal', DialogsManager.getWidgetType( 'options' ).extend( 'rb-modal', modalProperties ) );
	}
};

module.exports = Modals;

},{}],64:[function(require,module,exports){
var presetsFactory;

presetsFactory = {

	getPresetsDictionary: function() {
		return {
			11: 100 / 9,
			12: 100 / 8,
			14: 100 / 7,
			16: 100 / 6,
			33: 100 / 3,
			66: 2 / 3 * 100,
			83: 5 / 6 * 100
		};
	},

	getAbsolutePresetValues: function( preset ) {
		var clonedPreset = rb.helpers.cloneObject( preset ),
			presetDictionary = this.getPresetsDictionary();

		_.each( clonedPreset, function( unitValue, unitIndex ) {
			if ( presetDictionary[ unitValue ] ) {
				clonedPreset[ unitIndex ] = presetDictionary[ unitValue ];
			}
		} );

		return clonedPreset;
	},

	getPresets: function( columnsCount, presetIndex ) {
		var presets = rb.helpers.cloneObject( rb.config.elements.section.presets );

		if ( columnsCount ) {
			presets = presets[ columnsCount ];
		}

		if ( presetIndex ) {
			presets = presets[ presetIndex ];
		}

		return presets;
	},

	getPresetByStructure: function( structure ) {
		var parsedStructure = this.getParsedStructure( structure );

		return this.getPresets( parsedStructure.columnsCount, parsedStructure.presetIndex );
	},

	getParsedStructure: function( structure ) {
		structure += ''; // Make sure this is a string

		return {
			columnsCount: structure.slice( 0, -1 ),
			presetIndex: structure.substr( -1 )
		};
	},

	getPresetSVG: function( preset, svgWidth, svgHeight, separatorWidth ) {
		svgWidth = svgWidth || 100;
		svgHeight = svgHeight || 50;
		separatorWidth = separatorWidth || 2;

		var absolutePresetValues = this.getAbsolutePresetValues( preset ),
			presetSVGPath = this._generatePresetSVGPath( absolutePresetValues, svgWidth, svgHeight, separatorWidth );

		return this._createSVGPreset( presetSVGPath, svgWidth, svgHeight );
	},

	_createSVGPreset: function( presetPath, svgWidth, svgHeight ) {
		var svg = document.createElementNS( 'http://www.w3.org/2000/svg', 'svg' );

		svg.setAttributeNS( 'http://www.w3.org/2000/xmlns/', 'xmlns:xlink', 'http://www.w3.org/1999/xlink' );
		svg.setAttribute( 'viewBox', '0 0 ' + svgWidth + ' ' + svgHeight );

		var path = document.createElementNS( 'http://www.w3.org/2000/svg', 'path' );

		path.setAttribute( 'd', presetPath );

		svg.appendChild( path );

		return svg;
	},

	_generatePresetSVGPath: function( preset, svgWidth, svgHeight, separatorWidth ) {
		var DRAW_SIZE = svgWidth - separatorWidth * ( preset.length - 1 );

		var xPointer = 0,
			dOutput = '';

		for ( var i = 0; i < preset.length; i++ ) {
			if ( i ) {
				dOutput += ' ';
			}

			var increment = preset[ i ] / 100 * DRAW_SIZE;

			xPointer += increment;

			dOutput += 'M' + ( +xPointer.toFixed( 4 ) ) + ',0';

			dOutput += 'V' + svgHeight;

			dOutput += 'H' + ( +( xPointer - increment ).toFixed( 4 ) );

			dOutput += 'V0Z';

			xPointer += separatorWidth;
		}

		return dOutput;
	}
};

module.exports = presetsFactory;

},{}],65:[function(require,module,exports){
var Schemes;

Schemes = function() {
	var self = this,
		styleRules = {},
		schemes = {},
		settings = {
			selectorWrapperPrefix: '.rb-widget-'
		},
		elements = {};

	var buildUI = function() {
		elements.$previewHead.append( elements.$style );
	};

	var initElements = function() {
		elements.$style = Backbone.$( '<style>', {
			id: 'rb-style-scheme'
		});

		elements.$previewHead = rb.$previewContents.find( 'head' );
	};

	var initSchemes = function() {
		schemes = rb.helpers.cloneObject( rb.config.schemes.items );
	};

	var addStyleRule = function( selector, property ) {
		if ( ! styleRules[ selector ] ) {
			styleRules[ selector ] = [];
		}

		styleRules[ selector ].push( property );
	};

	var fetchControlStyles = function( control, widgetType ) {
		_.each( control.selectors, function( cssProperty, selector ) {
			var currentSchemeValue = self.getSchemeValue( control.scheme.type, control.scheme.value, control.scheme.key ),
				outputSelector,
				outputCssProperty;

			if ( _.isEmpty( currentSchemeValue.value ) ) {
				return;
			}

			outputSelector = selector.replace( /\{\{WRAPPER\}\}/g, settings.selectorWrapperPrefix + widgetType );
			outputCssProperty = rb.getControlItemView().replaceStyleValues( cssProperty, currentSchemeValue.value );

			addStyleRule( outputSelector, outputCssProperty );
		} );
	};

	var fetchWidgetControlsStyles = function( widget, widgetType ) {
		var widgetSchemeControls = self.getWidgetSchemeControls( widget );

		_.each( widgetSchemeControls, function( control ) {
			fetchControlStyles( control, widgetType );
		} );
	};

	var fetchAllWidgetsSchemesStyle = function() {
		_.each( rb.config.widgets, function( widget, widgetType ) {
			fetchWidgetControlsStyles(  widget, widgetType  );
		} );
	};

	var parseSchemeStyle = function() {
		var stringOutput = '';

		_.each( styleRules, function( properties, selector ) {
			stringOutput += selector + '{' + properties.join( '' ) + '}';
		} );

		return stringOutput;
	};

	var resetStyleRules = function() {
		styleRules = {};
	};

	this.init = function() {
		initElements();
		buildUI();
		initSchemes();

		return self;
	};

	this.getWidgetSchemeControls = function( widget ) {
		return _.filter( widget.controls, function( control ) {
			return _.isObject( control.scheme );
		} );
	};

	this.getSchemes = function() {
		return schemes;
	};

	this.getEnabledSchemesTypes = function() {
		return rb.config.schemes.enabled_schemes;
	};

	this.getScheme = function( schemeType ) {
		return schemes[ schemeType ];
	};

	this.getSchemeValue = function( schemeType, value, key ) {
		if ( this.getEnabledSchemesTypes().indexOf( schemeType ) < 0 ) {
			return false;
		}

		var scheme = self.getScheme( schemeType ),
			schemeValue = scheme.items[ value ];

		if ( key && _.isObject( schemeValue ) ) {
			var clonedSchemeValue = rb.helpers.cloneObject( schemeValue );

			clonedSchemeValue.value = schemeValue.value[ key ];

			return clonedSchemeValue;
		}

		return schemeValue;
	};

	this.printSchemesStyle = function() {
		resetStyleRules();
		fetchAllWidgetsSchemesStyle();

		elements.$style.text( parseSchemeStyle() );
	};

	this.resetSchemes = function( schemeName ) {
		schemes[ schemeName ] = rb.helpers.cloneObject( rb.config.schemes.items[ schemeName ] );

		this.onSchemeChange();
	};

	this.saveScheme = function( schemeName ) {
		rb.config.schemes.items[ schemeName ].items = rb.helpers.cloneObject( schemes[ schemeName ].items );

		NProgress.start();

		rb.ajax.send( 'apply_scheme', {
			data: {
				scheme_name: schemeName,
				data: JSON.stringify( schemes[ schemeName ].items )
			},
			success: function() {
				NProgress.done();
			}
		} );
	};

	this.setSchemeValue = function( schemeName, itemKey, value ) {
		schemes[ schemeName ].items[ itemKey ].value = value;

		this.onSchemeChange();
	};

	this.onSchemeChange = function() {
		this.printSchemesStyle();
	};
};

module.exports = new Schemes();

},{}],66:[function(require,module,exports){
( function( $ ) {

	var Stylesheet = function() {
		var self = this,
			rules = {},
			devices = {};

		var getDeviceMaxValue = function( deviceName ) {
			var deviceNames = Object.keys( devices ),
				deviceNameIndex = deviceNames.indexOf( deviceName ),
				nextIndex = deviceNameIndex + 1;

			if ( nextIndex >= deviceNames.length ) {
				throw new RangeError( 'Max value for this device is out of range.' );
			}

			return devices[ deviceNames[ nextIndex ] ] - 1;
		};

		var queryToHash = function( query ) {
			var hash = [];

			$.each( query, function( endPoint ) {
				hash.push( endPoint + '_' + this );
			} );

			return hash.join( '-' );
		};

		var hashToQuery = function( hash ) {
			var query = {};

			hash = hash.split( '-' ).filter( String );

			hash.forEach( function( singleQuery ) {
				var queryParts = singleQuery.split( '_' ),
					endPoint = queryParts[0],
					deviceName = queryParts[1];

				query[ endPoint ] = 'max' === endPoint ? getDeviceMaxValue( deviceName ) : devices[ deviceName ];
			} );

			return query;
		};

		var addQueryHash = function( queryHash ) {
			rules[ queryHash ] = {};

			var hashes = Object.keys( rules );

			if ( hashes.length < 2 ) {
				return;
			}

			// Sort the devices from narrowest to widest
			hashes.sort( function( a, b ) {
				if ( 'all' === a ) {
					return -1;
				}

				if ( 'all' === b ) {
					return 1;
				}

				var aQuery = hashToQuery( a ),
					bQuery = hashToQuery( b );

				return bQuery.max - aQuery.max;
			} );

			var sortedRules = {};

			hashes.forEach( function( deviceName ) {
				sortedRules[ deviceName ] = rules[ deviceName ];
			} );

			rules = sortedRules;
		};

		this.addDevice = function( deviceName, deviceValue ) {
			devices[ deviceName ] = deviceValue;

			var deviceNames = Object.keys( devices );

			if ( deviceNames.length < 2 ) {
				return self;
			}

			// Sort the devices from narrowest to widest
			deviceNames.sort( function( a, b ) {
				return devices[ a ] - devices[ b ];
			} );

			var sortedDevices = {};

			deviceNames.forEach( function( deviceName ) {
				sortedDevices[ deviceName ] = devices[ deviceName ];
			} );

			devices = sortedDevices;

			return self;
		};

		var getQueryHashStyleFormat = function( queryHash ) {
			var query = hashToQuery( queryHash ),
				styleFormat = [];

			$.each( query, function( endPoint ) {
				styleFormat.push( '(' + endPoint + '-width:' + this + 'px)' );
			} );

			return '@media' + styleFormat.join( ' and ' );
		};

		this.addRules = function( selector, styleRules, query ) {
			var queryHash = 'all';

			if ( query ) {
				queryHash = queryToHash( query );
			}

			if ( ! rules[ queryHash ] ) {
				addQueryHash( queryHash );
			}

			if ( ! rules[ queryHash ][ selector ] ) {
				rules[ queryHash ][ selector ] = {};
			}

			if ( 'string' === typeof styleRules ) {
				styleRules = styleRules.split( ';' ).filter( String );

				var orderedRules = {};

				$.each( styleRules, function() {
					var property = this.split( /:(.*)?/ );

					if (typeof property[1] != 'undefined') {
						orderedRules[ property[0].trim() ] = property[1].trim().replace( ';', '' );
					}
				});

				styleRules = orderedRules;
			}

			$.extend( rules[ queryHash ][ selector ], styleRules );

			return self;
		};

		this.empty = function() {
			rules = {};
		};

		this.toString = function() {
			var styleText = '';

			$.each( rules, function( queryHash ) {
				var deviceText = Stylesheet.parseRules( this );

				if ( 'all' !== queryHash ) {
					deviceText = getQueryHashStyleFormat( queryHash ) + '{' + deviceText + '}';
				}

				styleText += deviceText;
			} );

			return styleText;
		};
	};

	Stylesheet.parseRules = function( rules ) {
		var parsedRules = '';

		$.each( rules, function( selector ) {
			var selectorContent = Stylesheet.parseProperties( this );

			if ( selectorContent ) {
				parsedRules += selector + '{' + selectorContent + '}';
			}
		} );

		return parsedRules;
	};

	Stylesheet.parseProperties = function( properties ) {
		var parsedProperties = '';

		$.each( properties, function( propertyKey ) {
			if ( this ) {
				parsedProperties += propertyKey + ':' + this + ';';
			}
		} );

		return parsedProperties;
	};

	module.exports = Stylesheet;
} )( jQuery );

},{}],67:[function(require,module,exports){
var BaseSettingsModel = require('rb-models/base-settings'),
	Stylesheet = require( 'rb-utils/stylesheet' ),
	BaseElementView;

BaseElementView = Marionette.CompositeView.extend( {
	tagName: 'div',

	id: function() {
		return this.getElementUniqueClass();
	},

	attributes: function() {
		var type = this.model.get( 'elType' );

		if ( 'widget'  === type ) {
			type = this.model.get( 'widgetType' );
		}
		return {
			'data-element_type': type
		};
	},

	baseEvents: {},

	elementEvents: {},

	stylesheet: null,
	$stylesheetElement: null,

	getElementType: function() {
		return this.model.get( 'elType' );
	},

	getChildType: function() {
		return rb.helpers.getElementChildType( this.getElementType() );
	},

	templateHelpers: function() {
		return {
			elementModel: this.model
		};
	},

	events: function() {
		return _.extend( {}, this.baseEvents, this.elementEvents );
	},

	getTemplateType: function() {
		return 'js';
	},

	initialize: function() {
		this.collection = this.model.get( 'elements' );

		if (this.collection) {
			this.listenTo(this.collection, 'add remove reset', this.onCollectionChanged, this);
		}

		this.listenTo( this.model.get( 'settings' ), 'change', this.onSettingsChanged, this );
		this.listenTo( this.model.get( 'editSettings' ), 'change', this.onSettingsChanged, this );

		this.on( 'render', function() {
			this.renderUI();
			this.runReadyTrigger();
		} );

		this.initRemoveDialog();

		this.initStylesheet();
	},

	addChildModel: function( model, options ) {
		return this.collection.add( model, options, true );
	},

	isCollectionFilled: function() {
		return false;
	},

	isInner: function() {
		return !! this.model.get( 'isInner' );
	},

	initRemoveDialog: function() {
		var removeDialog;

		this.getRemoveDialog = function() {
			if ( ! removeDialog ) {
				var elementTitle = this.model.getTitle();

				removeDialog = rb.dialogsManager.createWidget( 'confirm', {
					message: rb.translate('dialog_confirm_delete', [ elementTitle.toLowerCase() ] ),
					headerMessage: rb.translate('delete_element', [ elementTitle ] ),
					strings: {
						confirm: rb.translate('delete'),
						cancel: rb.translate('cancel')
					},
					defaultOption: 'confirm',
					onConfirm: _.bind( function() {
						this.model.destroy();
					}, this )
				} );
			}

			return removeDialog;
		};
	},

	initStylesheet: function() {
		this.stylesheet = new Stylesheet();

		var viewportBreakpoints = rb.config.viewportBreakpoints;

		this.stylesheet
			.addDevice( 'mobile', 0 )
			.addDevice( 'tablet', viewportBreakpoints.md )
			.addDevice( 'desktop', viewportBreakpoints.lg );
	},

	enqueueFonts: function() {
		_.each( this.model.get( 'settings' ).getFontControls(), _.bind( function( control ) {
			var fontFamilyName = this.model.getSetting( control.name );
			if ( _.isEmpty( fontFamilyName ) ) {
				return;
			}

			var isVisible = rb.helpers.isControlVisible( control, this.model.get( 'settings' ) );
			if ( ! isVisible ) {
				return;
			}

			rb.helpers.enqueueFont( fontFamilyName );
		}, this ) );
	},

	renderStyles: function() {
		var self = this,
			settings = self.model.get( 'settings' );

		self.stylesheet.empty();

		self.addStyleRules( settings.getStyleControls(), settings.attributes );

		if ( 'column' === self.model.get( 'elType' ) ) {
			var inlineSize = self.model.getSetting( '_inline_size' );

			if ( ! _.isEmpty( inlineSize ) ) {
				self.stylesheet.addRules( '#' + self.getElementUniqueClass(), { width: inlineSize + '%' }, { min: 'tablet' } );
			}
		}

		self.addStyleToDocument();
	},

	addStyleRules: function( controls, values, placeholders, replacements ) {
		var self = this;

		placeholders = placeholders || [ /\{\{WRAPPER}}/g ];

		replacements = replacements || [ '#' + self.getElementUniqueClass() ];

		_.each( controls, function( control ) {

			if ( control.styleFields ) {
				placeholders[1] = '{{CURRENT_ITEM}}';

				values[ control.name ].each( function( itemModel ) {
					replacements[1] = '.rb-repeater-item-' + itemModel.get( '_id' );

					self.addStyleRules( control.styleFields, itemModel.attributes, placeholders, replacements );
				} );
			}

			//self.addControlStyleRules( control, values, self.model.get( 'settings' ), placeholders, replacements );
			self.addControlStyleRules( control, values, self.model.get( 'settings' ).controls, placeholders, replacements );
		} );
	},

	addControlStyleRules: function( control, values, controlsStack, placeholders, replacements ) {
		var self = this;

		BaseElementView.addControlStyleRules( self.stylesheet, control, controlsStack, function( control ) {
			return self.getStyleControlValue( control, values );
		}, placeholders, replacements );
	},

	getStyleControlValue: function( control, values ) {
		var value = values[ control.name ];

		if ( control.selectors_dictionary ) {
			value = control.selectors_dictionary[ value ] || value;
		}

		if ( ! _.isNumber( value ) && _.isEmpty( value ) ) {
			return;
		}

		var isVisible = rb.helpers.isControlVisible( control, this.model.get( 'settings' ) );
		if ( ! isVisible ) {
			return;
		}

		return value;
	},

	addStyleToDocument: function() {
		var styleText = this.stylesheet.toString();

		if ( _.isEmpty( styleText ) && ! this.$stylesheetElement ) {
			return;
		}

		if ( ! this.$stylesheetElement ) {
			this.createStylesheetElement();
		}

		this.$stylesheetElement.text( styleText );
	},

	createStylesheetElement: function() {
		this.$stylesheetElement = Backbone.$( '<style>', { id: 'rb-style-' + this.model.cid } );

		rb.$previewContents.find( 'head' ).append( this.$stylesheetElement );
	},

	renderCustomClasses: function() {
		this.$el.addClass( 'rb-element' );

		var settings = this.model.get( 'settings' );

		_.each( settings.attributes, _.bind( function( value, attribute ) {
			if ( settings.isClassControl( attribute ) ) {
				var currentControl = settings.getControl( attribute );

				this.$el.removeClass( currentControl.prefix_class + settings.previous( attribute ) );

				var isVisible = rb.helpers.isControlVisible( currentControl, this.model.get( 'settings' ) );

				if ( isVisible && ! _.isEmpty( settings.get( attribute ) ) ) {
					this.$el.addClass( currentControl.prefix_class + settings.get( attribute ) );
					this.$el.addClass( _.result( this, 'className' ) );
				}
			}
		}, this ) );
	},

	renderUI: function() {
		this.renderStyles();
		this.renderCustomClasses();
		this.enqueueFonts();
	},

	runReadyTrigger: function() {
		_.defer( _.bind( function() {
			rbFrontend.elementsHandler.runReadyTrigger( this.$el );
		}, this ) );
	},

	getElementUniqueClass: function() {
		return 'rb-element-' + this.model.get( 'id' );
	},

	onCollectionChanged: function() {
		rb.setFlagliveChange( true );
	},

	onSettingsChanged: function( settings ) {
		if ( this.model.get( 'editSettings' ) !== settings ) {
			// Change flag only if server settings was changed
			rb.setFlagliveChange( true );
		}

		// Make sure is correct model
		if ( settings instanceof BaseSettingsModel ) {
			var isContentChanged = false;

			_.each( settings.changedAttributes(), function( settingValue, settingKey ) {
				var control = settings.getControl( settingKey );

				if ( ! control ) {
					return;
				}

				if ( control.force_render || ! settings.isStyleControl( settingKey ) && ! settings.isClassControl( settingKey ) ) {
					isContentChanged = true;
				}
			} );

			if ( ! isContentChanged ) {
				this.renderUI();
				return;
			}
		}

		// Re-render the template
		var templateType = this.getTemplateType();

		if ( 'js' === templateType ) {
			this.model.setHtmlCache();
			this.render();
			this.model.renderOnLeave = true;
		} else {
			this.model.renderRemoteServer();
		}
	},

	onClickRemove: function( event ) {
		event.preventDefault();
		event.stopPropagation();

		this.getRemoveDialog().show();
	}
}, {
	addControlStyleRules: function( stylesheet, control, controlsStack, valueCallback, placeholders, replacements ) {
		var value = valueCallback( control );

		if ( undefined === value ) {
			return;
		}

		_.each( control.selectors, function( cssProperty, selector ) {

			var outputCssProperty,
				query;

			try {
				outputCssProperty = cssProperty.replace( /\{\{(?:([^.}]+)\.)?([^}]*)}}/g, function( originalPhrase, controlName, placeholder ) {
                    var parserControl = control,
                        valueToInsert = value;

                    if ( controlName ) {
                        parserControl = _.findWhere( controlsStack, { name: controlName } );

                        valueToInsert = valueCallback( parserControl );
                    }

                    var parsedValue = rb.getControlItemView( parserControl.type ).getStyleValue( placeholder.toLowerCase(), valueToInsert );

                    if ( '' === parsedValue ) {
                        throw '';
                    }

                    return parsedValue;
				} );
			} catch ( e ) {
				return;
			}

			if ( _.isEmpty( outputCssProperty ) ) {
				return;
			}

            _.each( placeholders, function( placeholder, index ) {
                var placeholderPattern = new RegExp( placeholder, 'g' );

                selector = selector.replace( placeholderPattern, replacements[ index ] );
            } );

			if ( control.responsive && 'desktop' !== control.responsive ) {
				query = { max: control.responsive };
			}

			stylesheet.addRules( selector, outputCssProperty, query );
		} );
	}
} );

module.exports = BaseElementView;
},{"rb-models/base-settings":52,"rb-utils/stylesheet":66}],68:[function(require,module,exports){
var BaseElementView = require( 'rb-views/base-element' ),
	ElementEmptyView = require( 'rb-views/element-empty' ),
	WidgetView = require( 'rb-views/widget' ),
	ColumnView;

ColumnView = BaseElementView.extend( {
	template: Marionette.TemplateCache.get( '#dd-rb-element-column-content' ),

	elementEvents: {
		'click > .rb-element-overlay .rb-live-column-settings-list .rb-live-element-remove': 'onClickRemove',
		'click @ui.listTriggers': 'onClickTrigger'
	},

	getChildView: function( model ) {
		if ( 'section' === model.get( 'elType' ) ) {
			return require( 'rb-views/section' ); // We need to require the section dynamically
		}

		return WidgetView;
	},

	emptyView: ElementEmptyView,

	className: function() {
		var classes = 'rb-column',
			type = this.isInner() ? 'inner' : 'top';

		classes += ' rb-' + type + '-column';

		return classes;
	},

	childViewContainer: '> .rb-column-wrap > .rb-widget-wrap',

	triggers: {
		'click > .rb-element-overlay .rb-live-column-settings-list .rb-live-element-add': 'click:new',
		'click > .rb-element-overlay .rb-live-column-settings-list .rb-live-element-edit': 'click:edit',
		'click > .rb-element-overlay .rb-live-column-settings-list .rb-live-element-trigger': 'click:edit',
		'click > .rb-element-overlay .rb-live-column-settings-list .rb-live-element-duplicate': 'click:duplicate'
	},

	ui: {
		columnTitle: '.column-title',
		columnInner: '> .rb-column-wrap',
		listTriggers: '> .rb-element-overlay .rb-live-element-trigger'
	},

	behaviors: {
		Sortable: {
			behaviorClass: require( 'rb-behaviors/sortable' ),
			elChildType: 'widget'
		},
		Resizable: {
			behaviorClass: require( 'rb-behaviors/resizable' )
		},
		HandleDuplicate: {
			behaviorClass: require( 'rb-behaviors/handle-duplicate' )
		},
		Handlelive: {
			behaviorClass: require( 'rb-behaviors/handle-live' )
		},
		HandleEditMode: {
			behaviorClass: require( 'rb-behaviors/handle-edit-mode' )
		},
		HandleAddMode: {
			behaviorClass: require( 'rb-behaviors/duplicate' )
		},
		HandleElementsRelation: {
			behaviorClass: require( 'rb-behaviors/elements-relation' )
		}
	},

	initialize: function() {
		BaseElementView.prototype.initialize.apply( this, arguments );

		this.listenTo( rb.channels.data, 'widget:drag:start', this.onWidgetDragStart );
		this.listenTo( rb.channels.data, 'widget:drag:end', this.onWidgetDragEnd );
	},

	isDroppingAllowed: function( side, event ) {
		var elementView = rb.channels.panelElements.request( 'element:selected' ),
			elType = elementView.model.get( 'elType' );

		if ( 'section' === elType ) {
			return ! this.isInner();
		}

		return 'widget' === elType;
	},

	changeSizeUI: function() {
		var columnSize = this.model.getSetting( '_column_size' ),
			inlineSize = this.model.getSetting( '_inline_size' ),
			columnSizeTitle = parseFloat( inlineSize || columnSize ).toFixed( 1 ) + '%';

		this.$el.attr( 'data-col', columnSize );

		this.ui.columnTitle.html( columnSizeTitle );
	},

	getSortableOptions: function() {
		return {
			connectWith: '.rb-widget-wrap',
			items: '> .rb-element'
		};
	},

	// Events
	onCollectionChanged: function() {
		BaseElementView.prototype.onCollectionChanged.apply( this, arguments );

		this.changeChildContainerClasses();
	},

	changeChildContainerClasses: function() {
		var emptyClass = 'rb-element-empty',
			populatedClass = 'rb-element-populated';

		if ( this.collection.isEmpty() ) {
			this.ui.columnInner.removeClass( populatedClass ).addClass( emptyClass );
		} else {
			this.ui.columnInner.removeClass( emptyClass ).addClass( populatedClass );
		}
	},

	onRender: function() {
		var self = this;

		self.changeChildContainerClasses();
		self.changeSizeUI();

		self.$el.html5Droppable( {
			items: ' > .rb-column-wrap > .rb-widget-wrap > .rb-element, >.rb-column-wrap > .rb-widget-wrap > .rb-empty-view > .rb-first-add',
			axis: [ 'vertical' ],
			groups: [ 'rb-element' ],
			isDroppingAllowed: _.bind( self.isDroppingAllowed, self ),
			onDragEnter: function() {
				self.$el.addClass( 'rb-dragging-on-child' );
			},
			onDragging: function( side, event ) {
				event.stopPropagation();

				if ( this.dataset.side !== side ) {
					Backbone.$( this ).attr( 'data-side', side );
				}
			},
			onDragLeave: function() {
				self.$el.removeClass( 'rb-dragging-on-child' );

				Backbone.$( this ).removeAttr( 'data-side' );
			},
			onDropping: function( side, event ) {
				event.stopPropagation();

				var elementView = rb.channels.panelElements.request( 'element:selected' ),
					newIndex = Backbone.$( this ).index();

				if ( 'bottom' === side ) {
					newIndex++;
				}

				var itemData = {
					id: rb.helpers.getUniqueID(),
					elType: elementView.model.get( 'elType' )
				};

				if ( 'widget' === itemData.elType ) {
					itemData.widgetType = elementView.model.get( 'widgetType' );
				} else if ( 'section' === itemData.elType ) {
					itemData.elements = [];
					itemData.isInner = true;
				} else {
					return;
				}

				self.triggerMethod( 'request:add', itemData, { at: newIndex } );
			}
		} );
	},

	onClickTrigger: function( event ) {
		event.preventDefault();

		var $trigger = this.$( event.currentTarget ),
			isTriggerActive = $trigger.hasClass( 'rb-active' );

		this.ui.listTriggers.removeClass( 'rb-active' );

		if ( ! isTriggerActive ) {
			$trigger.addClass( 'rb-active' );
		}
	},

	onWidgetDragStart: function() {
		this.$el.addClass( 'rb-dragging' );
	},

	onWidgetDragEnd: function() {
		this.$el.removeClass( 'rb-dragging' );
	}
} );

module.exports = ColumnView;

},{"rb-behaviors/duplicate":1,"rb-behaviors/elements-relation":2,"rb-behaviors/handle-duplicate":3,"rb-behaviors/handle-edit-mode":4,"rb-behaviors/handle-live":5,"rb-behaviors/resizable":6,"rb-behaviors/sortable":7,"rb-views/base-element":67,"rb-views/element-empty":93,"rb-views/section":94,"rb-views/widget":96}],69:[function(require,module,exports){
var ControlBaseItemView = require( 'rb-views/controls/base' ),
	ControlAnimationItemView;

ControlAnimationItemView = ControlBaseItemView.extend( {

	onReady: function() {
		this.ui.select.select2();
	}
} );

module.exports = ControlAnimationItemView;

},{"rb-views/controls/base":73}],70:[function(require,module,exports){
var ControlBaseItemView = require( 'rb-views/controls/base' ),
	ControlAutocompleteProductsItemView;

ControlAutocompleteProductsItemView = ControlBaseItemView.extend( {

	ui: function() {
		var ui = ControlBaseItemView.prototype.ui.apply( this, arguments );

		ui.searchInput = '.rb-control-autocomplete-search';
		ui.selectedOptions = '.rb-control-selected-options';
		ui.selectedPreview = '.rb-control-selected-preview';
		ui.buttonProductRemove = '.rb-product-remove';

		return ui;
	},

	childEvents: {
		'click @ui.buttonProductRemove': 'onClickProductRemove',
	},


	onShow: function () {

		var self = this;

		self.ui.selectedPreview.sortable( {
		    axis: 'y',
            stop: function( event, ui ) {

		        var $selectBox = $(self.ui.selectedOptions).empty();

                $.map($(this).find('.rb-product'), function(el) {
                    $selectBox.append('<option value="' + $(el).data('product-id') + '" selected>p</option>');
                });

                $selectBox.trigger('change');
            }
        } );

		self.insertProducts(this.getControlValue());

		var p_auto_settings = {
			minChars: 3,
			autoFill: true,
			max: 20,
			matchContains: true,
			mustMatch: true,
			dataType: 'json',
			extraParams: {
				format: 'json',
				excludeIds: self.getSelectedProductsIds(),
				action: 'SearchProducts'
			},
			parse: function (data) {
				var parsed = [];
				if (data == null)
					return true;
				for (var i = 0; i < data.length; i++) {
					parsed[parsed.length] = {
						data: data[i],
						value: data[i].name,
						result: data[i].name
					};
				}
				return parsed;
			},
			formatItem: function (item) {
				return '<img src="' + item.image + '" style="width: 30px; max-height: 100%; margin-right: 5px; border: 1px dotted #cecece; display: inline-block; vertical-align: middle;" />(ID: ' + item.id + ') ' + item.name;
			},
			cacheLength: 0,
		};

		$(this.ui.searchInput).autocomplete(rbConfig.ajaxurl, p_auto_settings).result(function (event, data, formatted) {
			if (data == null)
				return false;

			var optionHtml = '<option value="' + data.id + '" selected>' + '(ID: ' + data.id+ ') ' + data.name + '</option>';
			var previewHtml = '<div class="rb-product" data-product-id="' + data.id + '"><div class="rb-repeater-row-handle-sortable"><i class="fa fa-ellipsis-v"></i></div><img class="rb-product-image" src="' + data.image + '" />' +
				'<div class="rb-product-info"><span class="rb-product-reference">(id: ' + data.id + ')</span>'
				+ data.name
				+ '<button data-product-id="' + data.id + '" class="rb-product-remove rb-product-remove2' + data.id + '"><i class="fa fa-remove"></i></button></div></div>';

			if ($(self.ui.searchInput).attr('data-single')) {
				$(self.ui.selectedOptions).html(optionHtml);
				$(self.ui.selectedPreview).html(previewHtml);
			} else{
				$(self.ui.selectedOptions).append(optionHtml);
				$(self.ui.selectedPreview).append(previewHtml);
			}



			$(self.ui.searchInput).setOptions({
				extraParams: {
					format: 'json',
					excludeIds : self.getSelectedProductsIds(),
					action: 'SearchProducts'
				}
			});

			$(self.ui.selectedOptions).trigger('change');
			$(this).val('');

		});

	},

	onClickProductRemove: function(domEvent) {

		var $product = $(domEvent.currentTarget);
		var productId = $product.data('product-id');

		$product.parents('.rb-product').first().remove();

		$(this.ui.selectedOptions).find('option[value=' + productId +' ]').remove();

		$(this.ui.searchInput).setOptions({
			extraParams: {
				format: 'json',
				excludeIds : this.getSelectedProductsIds(),
				action: 'SearchProducts'
			}
		});

		$(this.ui.selectedOptions).trigger('change');


	},

	getSelectedProductsIds: function() {

		var ids = $(this.ui.selectedOptions).val();

		if (_.isUndefined(ids)|| ids == null) {
			return '';
		}
		else{
			return ids.toString();
		}

	},

	onBeforeDestroy: function() {

		$(this.ui.searchInput).unautocomplete();

	},

	insertProducts: function(ids) {

		if (_.isUndefined(ids)|| ids == null) {
			return;
		}

		var products = null;
		var self = this;

		rb.ajax.send( 'GetProducts', {
			data: {
				ids: ids.toString(),
			},
			success: function(data) {
				_.each( data, function( data ) {
					data.id = data.id_product;
					$(self.ui.selectedPreview).append('<div class="rb-product" data-product-id="' + data.id + '"><div class="rb-repeater-row-handle-sortable"><i class="fa fa-ellipsis-v"></i></div><img class="rb-product-image" src="' + data.image + '" />' +
						'<div class="rb-product-info"><span class="rb-product-reference">(id: ' + data.id + ')</span>'
						+ data.name
						+ '<button data-product-id="' + data.id + '" class="rb-product-remove"><i class="fa fa-remove"></i></button></div></div>');
				});
			}
		} );
		return products;
	}

} );

module.exports = ControlAutocompleteProductsItemView;



},{"rb-views/controls/base":73}],71:[function(require,module,exports){
var ControlBaseItemView = require( 'rb-views/controls/base' ),
	ControlBaseMultipleItemView;

ControlBaseMultipleItemView = ControlBaseItemView.extend( {

	applySavedValue: function() {
		var values = this.getControlValue(),
			$inputs = this.$( '[data-setting]' ),
			self = this;

		_.each( values, function( value, key ) {
			var $input = $inputs.filter( function() {
				return key === this.dataset.setting;
			} );

			self.setInputValue( $input, value );
		} );
	},

	getControlValue: function( key ) {
		var values = this.elementSettingsModel.get( this.model.get( 'name' ) );

		if ( ! Backbone.$.isPlainObject( values ) ) {
			return {};
		}

		if ( key ) {
			return values[ key ] || '';
		}

		return rb.helpers.cloneObject( values );
	},

	setValue: function( key, value ) {
		var values = this.getControlValue();

		if ( 'object' === typeof key ) {
			_.each( key, function( internalValue, internalKey ) {
				values[ internalKey ] = internalValue;
			} );
		} else {
			values[ key ] = value;
		}

		this.setSettingsModel( values );
	},

	updateElementModel: function( event ) {
		var inputValue = this.getInputValue( event.currentTarget ),
			key = event.currentTarget.dataset.setting;

		this.setValue( key, inputValue );
	}
}, {
	// Static methods
	replaceStyleValues: function( cssProperty, controlValue ) {
		if ( ! _.isObject( controlValue ) ) {
			return ''; // invalid
		}

		// Trying to retrieve whole the related properties
		// according to the string matches.
		// When one of the properties is empty, aborting
		// the action and returning an empty string.
		try {
			return cssProperty.replace( /\{\{([A-Z]+)}}/g, function( fullMatch, pureMatch ) {
				var value = controlValue[ pureMatch.toLowerCase() ];

				if ( '' === value ) {
					throw '';
				}

				return value;
			} );
		} catch ( exception ) {
			return '';
		}
	},
	getStyleValue: function( placeholder, controlValue ) {
		if ( ! _.isObject( controlValue ) ) {
			return ''; // invalid
		}

		return controlValue[ placeholder ];
	}
} );

module.exports = ControlBaseMultipleItemView;

},{"rb-views/controls/base":73}],72:[function(require,module,exports){
var ControlBaseMultipleItemView = require( 'rb-views/controls/base-multiple' ),
	ControlBaseUnitsItemView;

ControlBaseUnitsItemView = ControlBaseMultipleItemView.extend( {

	getCurrentRange: function() {
		return this.getUnitRange( this.getControlValue( 'unit' ) );
	},

	getUnitRange: function( unit ) {
		var ranges = this.model.get( 'range' );

		if ( ! ranges || ! ranges[ unit ] ) {
			return false;
		}

		return ranges[ unit ];
	}
} );

module.exports = ControlBaseUnitsItemView;

},{"rb-views/controls/base-multiple":71}],73:[function(require,module,exports){
var ControlBaseItemView;

ControlBaseItemView = Marionette.CompositeView.extend( {
	ui: function() {
		return {
			input: 'input[data-setting][type!="checkbox"][type!="radio"]',
			checkbox: 'input[data-setting][type="checkbox"]',
			radio: 'input[data-setting][type="radio"]',
			select: 'select[data-setting]',
			textarea: 'textarea[data-setting]',
			controlTitle: '.rb-control-title',
			responsiveSwitchers: '.rb-responsive-switcher',
			switcherDesktop: '.rb-responsive-switcher-desktop'
		};
	},

	className: function() {
		// TODO: Any better classes for that?
		var classes = 'rb-control rb-control-' + this.model.get( 'name' ) + ' rb-control-type-' + this.model.get( 'type' ),
			modelClasses = this.model.get( 'classes' ),
			responsiveControl = this.model.get( 'responsive' );

		if ( ! _.isEmpty( modelClasses ) ) {
			classes += ' ' + modelClasses;
		}

		if ( ! _.isEmpty( this.model.get( 'section' ) ) ) {
			classes += ' rb-control-under-section';
		}

		if ( ! _.isEmpty( responsiveControl ) ) {
			classes += ' rb-control-responsive-' + responsiveControl;
		}

		return classes;
	},

	getTemplate: function() {
		return Marionette.TemplateCache.get( '#dd-rb-control-' + this.model.get( 'type' ) + '-content' );
	},

	templateHelpers: function() {
		var controlData = {
			controlValue: this.getControlValue(),
			_cid: this.model.cid
		};

		return {
			data: _.extend( {}, this.model.toJSON(), controlData )
		};
	},

	baseEvents: {
		'input @ui.input': 'onBaseInputChange',
		'change @ui.checkbox': 'onBaseInputChange',
		'change @ui.radio': 'onBaseInputChange',
		'input @ui.textarea': 'onBaseInputChange',
		'change @ui.select': 'onBaseInputChange',
		'click @ui.switcherDesktop': 'onSwitcherDesktopClick',
		'click @ui.responsiveSwitchers': 'onSwitcherClick'
	},

	childEvents: {},

	events: function() {
		return _.extend( {}, this.baseEvents, this.childEvents );
	},

	initialize: function( options ) {
		this.elementSettingsModel = options.elementSettingsModel;

		var controlType = this.model.get( 'type' ),
			controlSettings = Backbone.$.extend( true, {}, rb.config.controls[ controlType ], this.model.attributes );

		this.model.set( controlSettings );

		this.listenTo( this.elementSettingsModel, 'change', this.toggleControlVisibility );
		this.listenTo( this.elementSettingsModel, 'control:switch:tab', this.onControlSwitchTab );
	},

	getControlValue: function() {
		return this.elementSettingsModel.get( this.model.get( 'name' ) );
	},

	isValidValue: function( value ) {
		return true;
	},

	setValue: function( value ) {
		this.setSettingsModel( value );
	},

	setSettingsModel: function( value ) {
		if ( true !== this.isValidValue( value ) ) {
			this.triggerMethod( 'settings:error' );
			return;
		}

		this.elementSettingsModel.set( this.model.get( 'name' ), value );

		this.triggerMethod( 'settings:change' );
	},

	applySavedValue: function() {
		this.setInputValue( '[data-setting="' + this.model.get( 'name' ) + '"]', this.getControlValue() );
	},

	getEditSettings: function( setting ) {
		var settings = this.getOption( 'elementEditSettings' ).toJSON();

		if ( setting ) {
			return settings[ setting ];
		}

		return settings;
	},

	setEditSetting: function( settingKey, settingValue ) {
		var settings = this.getOption( 'elementEditSettings' );

		settings.set( settingKey, settingValue );
	},

	getInputValue: function( input ) {
		var $input = this.$( input ),
			inputValue = $input.val(),
			inputType = $input.attr( 'type' );

		if ( -1 !== [ 'radio', 'checkbox' ].indexOf( inputType ) ) {
			return $input.prop( 'checked' ) ? inputValue : '';
		}

		return inputValue;
	},

	// This method used inside of repeater
	getFieldTitleValue: function() {
		return this.getControlValue();
	},

	setInputValue: function( input, value ) {
		var $input = this.$( input ),
			inputType = $input.attr( 'type' );

		if ( 'checkbox' === inputType ) {
			$input.prop( 'checked', !! value );
		} else if ( 'radio' === inputType ) {
			$input.filter( '[value="' + value + '"]' ).prop( 'checked', true );
		} else if ( 'select2' === inputType ) {
			// don't touch
		} else {
			$input.val( value );
		}
	},

	onSettingsError: function() {
		this.$el.addClass( 'rb-error' );
	},

	onSettingsChange: function() {
		this.$el.removeClass( 'rb-error' );
	},

	onRender: function() {
		this.applySavedValue();

		var layoutType = this.model.get( 'label_block' ) ? 'block' : 'inline',
			showLabel = this.model.get( 'show_label' ),
			elClasses = 'rb-label-' + layoutType;

		elClasses += ' rb-control-separator-' + this.model.get( 'separator' );

		if ( ! showLabel ) {
			elClasses += ' rb-control-hidden-label';
		}

		this.$el.addClass( elClasses );
		this.renderResponsiveSwitchers();

		this.triggerMethod( 'ready' );
		this.toggleControlVisibility();
	},

	onBaseInputChange: function( event ) {
		this.updateElementModel( event );

		this.triggerMethod( 'input:change', event );
	},

	onSwitcherClick: function( event ) {
		var device = Backbone.$( event.currentTarget ).data( 'device' );

		rb.changeDeviceMode( device );
	},

	onSwitcherDesktopClick: function() {
		rb.getPanelView().getCurrentPageView().$el.toggleClass( 'rb-responsive-switchers-open' );
	},

	renderResponsiveSwitchers: function() {
		if ( _.isEmpty( this.model.get( 'responsive' ) ) ) {
			return;
		}

		var templateHtml = Backbone.$( '#dd-rb-control-responsive-switchers' ).html();

		this.ui.controlTitle.after( templateHtml );
	},

	toggleControlVisibility: function() {
		var isVisible = rb.helpers.isControlVisible( this.model, this.elementSettingsModel );

		this.$el.toggleClass( 'rb-hidden-control', ! isVisible );

		rb.channels.data.trigger( 'scrollbar:update' );
	},

	onControlSwitchTab: function( activeTab ) {
		this.$el.toggleClass( 'rb-active-tab', ( activeTab === this.model.get( 'tab' ) ) );

		rb.channels.data.trigger( 'scrollbar:update' );
	},

	onReady: function() {},

	updateElementModel: function( event ) {
		this.setValue( this.getInputValue( event.currentTarget ) );
	}
}, {
	// Static methods
	replaceStyleValues: function( cssProperty, controlValue ) {
		var replaceArray = { '\{\{VALUE\}\}': controlValue };

		return rb.helpers.stringReplaceAll( cssProperty, replaceArray );
	},
	getStyleValue: function( placeholder, controlValue ) {
		return controlValue;
	}
} );

module.exports = ControlBaseItemView;

},{}],74:[function(require,module,exports){
var ControlMultipleBaseItemView = require( 'rb-views/controls/base-multiple' ),
	ControlBoxShadowItemView;

ControlBoxShadowItemView = ControlMultipleBaseItemView.extend( {
	ui: function() {
		var ui = ControlMultipleBaseItemView.prototype.ui.apply( this, arguments );

		ui.sliders = '.rb-slider';
		ui.colors = '.rb-box-shadow-color-picker';

		return ui;
	},

	childEvents: {
		'slide @ui.sliders': 'onSlideChange'
	},

	initSliders: function() {
		var value = this.getControlValue();

		this.ui.sliders.each( function() {
			var $slider = Backbone.$( this ),
				$input = $slider.next( '.rb-slider-input' ).find( 'input' );

			$slider.slider( {
				value: value[ this.dataset.input ],
				min: +$input.attr( 'min' ),
				max: +$input.attr( 'max' )
			} );
		} );
	},

	initColors: function() {
		var self = this;

		this.ui.colors.wpColorPicker( {
			change: function() {
				var $this = Backbone.$( this ),
					type = $this.data( 'setting' );

				self.setValue( type, $this.wpColorPicker( 'color' ) );
			},

			clear: function() {
				self.setValue( this.dataset.setting, '' );
			},

			width: 251
		} );
	},

	onInputChange: function( event ) {
		var type = event.currentTarget.dataset.setting,
			$slider = this.ui.sliders.filter( '[data-input="' + type + '"]' );

		$slider.slider( 'value', this.getControlValue( type ) );
	},

	onReady: function() {
		this.initSliders();
		this.initColors();
	},

	onSlideChange: function( event, ui ) {
		var type = event.currentTarget.dataset.input,
			$input = this.ui.input.filter( '[data-setting="' + type + '"]' );

		$input.val( ui.value );
		this.setValue( type, ui.value );
	},

	onBeforeDestroy: function() {
		this.ui.colors.each( function() {
			var $color = Backbone.$( this );

			if ( $color.wpColorPicker( 'instance' ) ) {
				$color.wpColorPicker( 'close' );
			}
		} );

		this.$el.remove();
	}
} );

module.exports = ControlBoxShadowItemView;

},{"rb-views/controls/base-multiple":71}],75:[function(require,module,exports){
var ControlBaseItemView = require( 'rb-views/controls/base' ),
	ControlChooseItemView;

ControlChooseItemView = ControlBaseItemView.extend( {
	ui: function() {
		var ui = ControlBaseItemView.prototype.ui.apply( this, arguments );

		ui.inputs = '[type="radio"]';

		return ui;
	},

	childEvents: {
		'mousedown label': 'onMouseDownLabel',
		'click @ui.inputs': 'onClickInput',
		'change @ui.inputs': 'updateElementModel'
	},

	onMouseDownLabel: function( event ) {
		var $clickedLabel = this.$( event.currentTarget ),
			$selectedInput = this.$( '#' + $clickedLabel.attr( 'for' ) );

		$selectedInput.data( 'checked', $selectedInput.prop( 'checked' ) );
	},

	onClickInput: function( event ) {
		if ( ! this.model.get( 'toggle' ) ) {
			return;
		}

		var $selectedInput = this.$( event.currentTarget );

		if ( $selectedInput.data( 'checked' ) ) {
			$selectedInput.prop( 'checked', false ).trigger( 'change' );
		}
	},

	onRender: function() {
		ControlBaseItemView.prototype.onRender.apply( this, arguments );

		var currentValue = this.getControlValue();

		if ( currentValue ) {
			this.ui.inputs.filter( '[value="' + currentValue + '"]' ).prop( 'checked', true );
		} else if ( ! this.model.get( 'toggle' ) ) {
			this.ui.inputs.first().prop( 'checked', true ).trigger( 'change' );
		}
	}
} );

module.exports = ControlChooseItemView;

},{"rb-views/controls/base":73}],76:[function(require,module,exports){
var ControlBaseItemView = require( 'rb-views/controls/base' ),
	ControlColorItemView;

ControlColorItemView = ControlBaseItemView.extend( {
	ui: function() {
		var ui = ControlBaseItemView.prototype.ui.apply( this, arguments );

		ui.picker = '.color-picker-hex';

		return ui;
	},

	onReady: function() {
		this.ui.picker.wpColorPicker( {
			change: _.bind( function() {
				this.setValue( this.ui.picker.wpColorPicker( 'color' ) );
			}, this ),

			clear: _.bind( function() {
				this.setValue( '' );
			}, this ),

			width: 251
		} ).wpColorPicker( 'instance' )
			.wrap.find( '> .wp-picker-input-wrap > .wp-color-picker' )
			.removeAttr( 'maxlength' );
	},

	onBeforeDestroy: function() {
		if ( this.ui.picker.wpColorPicker( 'instance' ) ) {
			this.ui.picker.wpColorPicker( 'close' );
		}
		this.$el.remove();
	}
} );

module.exports = ControlColorItemView;

},{"rb-views/controls/base":73}],77:[function(require,module,exports){
var ControlBaseUnitsItemView = require( 'rb-views/controls/base-units' ),
	ControlDimensionsItemView;

ControlDimensionsItemView = ControlBaseUnitsItemView.extend( {
	ui: function() {
		var ui = ControlBaseUnitsItemView.prototype.ui.apply( this, arguments );

		ui.controls = '.rb-control-dimension > input:enabled';
		ui.link = 'button.rb-link-dimensions';

		return ui;
	},

	childEvents: {
		'click @ui.link': 'onLinkDimensionsClicked'
	},

	defaultDimensionValue: 0,

	initialize: function() {
		ControlBaseUnitsItemView.prototype.initialize.apply( this, arguments );

		// TODO: Need to be in helpers, and not in variable
		this.model.set( 'allowed_dimensions', this.filterDimensions( this.model.get( 'allowed_dimensions' ) ) );
	},

	getPossibleDimensions: function() {
		return [
			'top',
			'right',
			'bottom',
			'left'
		];
	},

	filterDimensions: function( filter ) {
		filter = filter || 'all';

		var dimensions = this.getPossibleDimensions();

		if ( 'all' === filter ) {
			return dimensions;
		}

		if ( ! _.isArray( filter ) ) {
			if ( 'horizontal' === filter ) {
				filter = [ 'right', 'left' ];
			} else if ( 'vertical' === filter ) {
				filter = [ 'top', 'bottom' ];
			}
		}

		return filter;
	},

	onReady: function() {
		var currentValue = this.getControlValue();

		if ( ! this.isLinkedDimensions() ) {
			this.ui.link.addClass( 'unlinked' );

			this.ui.controls.each( _.bind( function( index, element ) {
				var value = currentValue[ element.dataset.setting ];

				if ( _.isEmpty( value ) ) {
					value = this.defaultDimensionValue;
				}

				this.$( element ).val( value );
			}, this ) );
		}

		this.fillEmptyDimensions();
	},

	updateDimensionsValue: function() {
		var currentValue = {},
			dimensions = this.getPossibleDimensions(),
			$controls = this.ui.controls;

		dimensions.forEach( _.bind( function( dimension ) {
			var $element = $controls.filter( '[data-setting="' + dimension + '"]' );

			currentValue[ dimension ] = $element.length ? $element.val() : this.defaultDimensionValue;
		}, this ) );

		this.setValue( currentValue );
	},

	fillEmptyDimensions: function() {
		var dimensions = this.getPossibleDimensions(),
			allowedDimensions = this.model.get( 'allowed_dimensions' ),
			$controls = this.ui.controls;

		if ( this.isLinkedDimensions() ) {
			return;
		}

		dimensions.forEach( _.bind( function( dimension ) {
			var $element = $controls.filter( '[data-setting="' + dimension + '"]' ),
				isAllowedDimension = -1 !== _.indexOf( allowedDimensions, dimension );

			if ( isAllowedDimension && $element.length && _.isEmpty( $element.val() ) ) {
				$element.val( this.defaultDimensionValue );
			}

		}, this ) );
	},

	updateDimensions: function() {
		this.fillEmptyDimensions();
		this.updateDimensionsValue();
	},

	resetDimensions: function() {
		this.ui.controls.val( '' );

		this.updateDimensionsValue();
	},

	onInputChange: function( event ) {
		var inputSetting = event.target.dataset.setting;

		if ( 'unit' === inputSetting ) {
			this.resetDimensions();
		}

		if ( ! _.contains( this.getPossibleDimensions(), inputSetting ) ) {
			return;
		}

		if ( this.isLinkedDimensions() ) {
			var $thisControl = this.$( event.target );

			this.ui.controls.val( $thisControl.val() );
		}

		this.updateDimensions();
	},

	onLinkDimensionsClicked: function( event ) {
		event.preventDefault();
		event.stopPropagation();

		this.ui.link.toggleClass( 'unlinked' );

		this.setValue( 'isLinked', ! this.ui.link.hasClass( 'unlinked' ) );

		if ( this.isLinkedDimensions() ) {
			// Set all controls value from the first control.
			this.ui.controls.val( this.ui.controls.eq( 0 ).val() );
		}

		this.updateDimensions();
	},

	isLinkedDimensions: function() {
		return this.getControlValue( 'isLinked' );
	}
} );

module.exports = ControlDimensionsItemView;

},{"rb-views/controls/base-units":72}],78:[function(require,module,exports){
var ControlBaseItemView = require( 'rb-views/controls/base' ),
	ControlFontItemView;

ControlFontItemView = ControlBaseItemView.extend( {
	onReady: function() {
		this.ui.select.select2( {
			dir: rb.config.is_rtl ? 'rtl' : 'ltr'
		} );
	},

	templateHelpers: function() {
		var helpers = ControlBaseItemView.prototype.templateHelpers.apply( this, arguments );

		helpers.getFontsByGroups = _.bind( function( groups ) {
			var fonts = this.model.get( 'fonts' ),
				filteredFonts = {};

			_.each( fonts, function( fontType, fontName ) {
				if ( _.isArray( groups ) && _.contains( groups, fontType ) || fontType === groups ) {
					filteredFonts[ fontName ] = fontType;
				}
			} );

			return filteredFonts;
		}, this );

		return helpers;
	}
} );

module.exports = ControlFontItemView;

},{"rb-views/controls/base":73}],79:[function(require,module,exports){
var ControlBaseItemView = require( 'rb-views/controls/base' ),
	ControlMediaItemView;

ControlMediaItemView = ControlBaseItemView.extend( {
	ui: function() {
		var ui = ControlBaseItemView.prototype.ui.apply( this, arguments );

		ui.addImages = '.rb-control-gallery-add';
		ui.clearGallery = '.rb-control-gallery-clear';
		ui.galleryThumbnails = '.rb-control-gallery-thumbnails';

		return ui;
	},

	childEvents: {
		'click @ui.addImages': 'onAddImagesClick',
		'click @ui.clearGallery': 'onClearGalleryClick',
		'click @ui.galleryThumbnails': 'onGalleryThumbnailsClick'
	},

	onReady: function() {
		var hasImages = this.hasImages();

		this.$el
		    .toggleClass( 'rb-gallery-has-images', hasImages )
		    .toggleClass( 'rb-gallery-empty', ! hasImages );

		this.initRemoveDialog();
	},

	hasImages: function() {
		return !! this.getControlValue().length;
	},

	openFrame: function( action ) {
		this.initFrame( action );

		this.frame.open();
	},

	initFrame: function( action ) {
		var frameStates = {
			create: 'gallery',
			add: 'gallery-library',
			edit: 'gallery-edit'
		};

		var options = {
			frame:  'post',
			multiple: true,
			state: frameStates[ action ],
			button: {
				text: rb.translate('insert_media')
			}
		};

		if ( this.hasImages() ) {
			options.selection = this.fetchSelection();
		}

		this.frame = wp.media( options );

		// When a file is selected, run a callback.
		this.frame.on( {
			'update': this.select,
			'menu:render:default': this.menuRender,
			'content:render:browse': this.gallerySettings
		}, this );
	},

	menuRender: function( view ) {
		view.unset( 'insert' );
		view.unset( 'featured-image' );
	},

	gallerySettings: function( browser ) {
		browser.sidebar.on( 'ready', function() {
			browser.sidebar.unset( 'gallery' );
		} );
	},

	fetchSelection: function() {
		var attachments = wp.media.query( {
			orderby: 'post__in',
			order: 'ASC',
			type: 'image',
			perPage: -1,
			post__in: _.pluck( this.getControlValue(), 'id' )
		} );

		return new wp.media.model.Selection( attachments.models, {
			props: attachments.props.toJSON(),
			multiple: true
		} );
	},

	/**
	 * Callback handler for when an attachment is selected in the media modal.
	 * Gets the selected image information, and sets it within the control.
	 */
	select: function( selection ) {
		var images = [];

		selection.each( function( image ) {
			images.push( {
				id: image.get( 'id' ),
				url: image.get( 'url' )
			} );
		} );

		this.setValue( images );

		this.render();
	},

	onBeforeDestroy: function() {
		if ( this.frame ) {
			this.frame.off();
		}

		this.$el.remove();
	},

	resetGallery: function() {
		this.setValue( '' );

		this.render();
	},

	initRemoveDialog: function() {
		var removeDialog;

		this.getRemoveDialog = function() {
			if ( ! removeDialog ) {
				removeDialog = rb.dialogsManager.createWidget( 'confirm', {
					message: rb.translate('dialog_confirm_gallery_delete'),
					headerMessage: rb.translate('delete_gallery'),
					strings: {
						confirm: rb.translate('delete'),
						cancel: rb.translate('cancel')
					},
					defaultOption: 'confirm',
					onConfirm: _.bind( this.resetGallery, this )
				} );
			}

			return removeDialog;
		};
	},

	onAddImagesClick: function() {
		this.openFrame( this.hasImages() ? 'add' : 'create' );
	},

	onClearGalleryClick: function() {
		this.getRemoveDialog().show();
	},

	onGalleryThumbnailsClick: function() {
		this.openFrame( 'edit' );
	}
} );

module.exports = ControlMediaItemView;

},{"rb-views/controls/base":73}],80:[function(require,module,exports){
var ControlBaseItemView = require( 'rb-views/controls/base' ),
	ControlIconItemView;

ControlIconItemView = ControlBaseItemView.extend( {

	initialize: function() {
		ControlBaseItemView.prototype.initialize.apply( this, arguments );

		this.filterIcons();
	},

	filterIcons: function() {
		var icons = this.model.get( 'icons' ),
			include = this.model.get( 'include' ),
			exclude = this.model.get( 'exclude' );

		if ( include ) {
			var filteredIcons = {};

			_.each( include, function( iconKey ) {
				filteredIcons[ iconKey ] = icons[ iconKey ];
			} );

			this.model.set( 'icons', filteredIcons );
			return;
		}

		if ( exclude ) {
			_.each( exclude, function( iconKey ) {
				delete icons[ iconKey ];
			} );
		}
	},

	iconsList: function( icon ) {
		if ( ! icon.id ) {
			return icon.text;
		}

		return Backbone.$(
			'<span><i class="' + icon.id + '"></i> ' + icon.text + '</span>'
		);
	},

	getFieldTitleValue: function() {
		var controlValue = this.getControlValue();

		return controlValue.replace( /^fa fa-/, '' ).replace( '-', ' ' );
	},

	onReady: function() {
		this.ui.select.select2( {
			allowClear: true,
			templateResult: _.bind( this.iconsList, this ),
			templateSelection: _.bind( this.iconsList, this )
		} );
	}
} );

module.exports = ControlIconItemView;

},{"rb-views/controls/base":73}],81:[function(require,module,exports){
var ControlMultipleBaseItemView = require( 'rb-views/controls/base-multiple' ),
	ControlImageDimensionsItemView;

ControlImageDimensionsItemView = ControlMultipleBaseItemView.extend( {
	ui: function() {
		return {
			inputWidth: 'input[data-setting="width"]',
			inputHeight: 'input[data-setting="height"]',

			btnApply: 'button.rb-image-dimensions-apply-button'
		};
	},

	// Override the base events
	baseEvents: {
		'click @ui.btnApply': 'onApplyClicked'
	},

	onApplyClicked: function( event ) {
		event.preventDefault();

		this.setValue( {
			width: this.ui.inputWidth.val(),
			height: this.ui.inputHeight.val()
		} );
	}
} );

module.exports = ControlImageDimensionsItemView;

},{"rb-views/controls/base-multiple":71}],82:[function(require,module,exports){
var ControlMultipleBaseItemView = require( 'rb-views/controls/base-multiple' ),
	ControlMediaItemView;

ControlMediaItemView = ControlMultipleBaseItemView.extend( {
	ui: function() {
		var ui = ControlMultipleBaseItemView.prototype.ui.apply( this, arguments );

		ui.controlMedia = '.rb-control-media';
		ui.frameOpeners = '.rb-control-media-upload-button, .rb-control-media-image';
		ui.deleteButton = '.rb-control-media-delete';
		ui.fileField = '.rb-control-media-field';

		return ui;
	},

	childEvents: {
		'click @ui.frameOpeners': 'openFrame',
		'click @ui.deleteButton': 'deleteImage',
		'input @ui.fileField': 'select'
	},

	onReady: function() {
		if ( _.isEmpty( this.getControlValue( 'url' ) ) ) {
			this.ui.controlMedia.addClass( 'media-empty' );
		}
	},

	openFrame: function() {
		openPsFileManager('rb-control-media-field-' + this.model.cid);
	},

	deleteImage: function() {
		this.setValue( {
			url: '',
			id: ''
		} );

		this.render();
	},

	select: function() {
		var attachment = this.ui.fileField.val();

		if ( attachment) {
			this.setValue( {
				url: attachment,
				id: 1
			} );

			this.render();
			this.ui.fileField.val(attachment);
		}
	},

	onBeforeDestroy: function() {
		this.$el.remove();
	}
} );

module.exports = ControlMediaItemView;

},{"rb-views/controls/base-multiple":71}],83:[function(require,module,exports){
var RepeaterRowView;

RepeaterRowView = Marionette.CompositeView.extend( {
	template: Marionette.TemplateCache.get( '#dd-rb-repeater-row' ),

	className: 'repeater-fields',

	ui: {
		duplicateButton: '.rb-repeater-tool-duplicate',
		editButton: '.rb-repeater-tool-edit',
		removeButton: '.rb-repeater-tool-remove',
		itemTitle: '.rb-repeater-row-item-title'
	},

	triggers: {
		'click @ui.removeButton': 'click:remove',
		'click @ui.duplicateButton': 'click:duplicate',
		'click @ui.itemTitle': 'click:edit'
	},

	templateHelpers: function() {
		return {
			itemIndex: this.getOption( 'itemIndex' )
		};
	},

	childViewContainer: '.rb-repeater-row-controls',

	getChildView: function( item ) {
		var controlType = item.get( 'type' );
		return rb.getControlItemView( controlType );
	},

	childViewOptions: function() {
		return {
			elementSettingsModel: this.model
		};
	},

	updateIndex: function( newIndex ) {
		this.itemIndex = newIndex;
		this.setTitle();
	},

	setTitle: function() {
		var titleField = this.getOption('titleField'),
			title;

		if ( titleField ) {
			var changerControlModel = this.collection.find( { name: titleField } ),
				changerControlView = this.children.findByModelCid( changerControlModel.cid );

			title = changerControlView.getFieldTitleValue();
		}

		if ( ! title ) {
			title = rb.translate( 'Item #{0}', [ this.getOption( 'itemIndex' ) ] );
		}

		this.ui.itemTitle.text( title );
	},

	initialize: function( options ) {
		this.elementSettingsModel = options.elementSettingsModel;

		this.itemIndex = 0;

		// Collection for Controls list
		this.collection = new Backbone.Collection( options.controlFields );

		if ( options.titleField ) {
			this.listenTo( this.model, 'change:' + options.titleField, this.setTitle );
		}
	},

	onRender: function() {
		this.setTitle();
	}
} );

module.exports = RepeaterRowView;

},{}],84:[function(require,module,exports){
var ControlBaseItemView = require( 'rb-views/controls/base' ),
	RepeaterRowView = require( 'rb-views/controls/repeater-row' ),
	ControlRepeaterItemView;

ControlRepeaterItemView = ControlBaseItemView.extend( {
	ui: {
		btnAddRow: '.rb-repeater-add',
		fieldContainer: '.rb-repeater-fields'
	},

	events: {
		'click @ui.btnAddRow': 'onButtonAddRowClick',
		'sortstart @ui.fieldContainer': 'onSortStart',
		'sortupdate @ui.fieldContainer': 'onSortUpdate'
	},

	childView: RepeaterRowView,

	childViewContainer: '.rb-repeater-fields',

	templateHelpers: function() {
		return {
			data: _.extend( {}, this.model.toJSON(), { controlValue: [] } )
		};
	},

	childViewOptions: function() {
		return {
			controlFields: this.model.get( 'fields' ),
			titleField: this.model.get( 'title_field' )
		};
	},

	initialize: function( options ) {
		ControlBaseItemView.prototype.initialize.apply( this, arguments );

		this.collection = this.elementSettingsModel.get( this.model.get( 'name' ) );

		this.listenTo( this.collection, 'change add remove reset', this.onCollectionChanged, this );
	},

	editRow: function( rowView ) {
		if ( this.currentEditableChild ) {
			this.currentEditableChild.getChildViewContainer( this.currentEditableChild ).removeClass( 'editable' );

			var sortedRowView = this.currentEditableChild,
				rowControls = sortedRowView.children._views;

			jQuery.each( rowControls, function() {
				if ( 'wysiwyg' === this.model.get( 'type' ) ) {
					tinymce.EditorManager.execCommand( 'mceRemoveEditor', true, this.editorID);
				}
			} );
		}


		if ( this.currentEditableChild === rowView ) {
			delete this.currentEditableChild;
			return;
		}

		rowView.getChildViewContainer( rowView ).addClass( 'editable' );

		this.currentEditableChild = rowView;


		var sortedRowView = this.currentEditableChild,
			rowControls = sortedRowView.children._views;

		jQuery.each( rowControls, function() {
			if ( 'wysiwyg' === this.model.get( 'type' ) ) {
				tinymce.EditorManager.execCommand( 'mceRemoveEditor', true, this.editorID);
				tinymce.EditorManager.execCommand('mceAddEditor', false, this.editorID);
			}
		} );

		this.updateActiveRow();
	},

	toggleMinRowsClass: function() {
		if ( ! this.model.get( 'prevent_empty' ) ) {
			return;
		}

		this.$el.toggleClass( 'rb-repeater-has-minimum-rows', 1 >= this.collection.length );
	},

	updateActiveRow: function() {
		var activeItemIndex = 0;


		if ( this.currentEditableChild ) {
			activeItemIndex = this.currentEditableChild.itemIndex;
		}

		this.setEditSetting( 'activeItemIndex', activeItemIndex );
	},

	updateChildIndexes: function() {
		this.children.each( _.bind( function( view ) {
			view.updateIndex( this.collection.indexOf( view.model ) + 1 );
		}, this ) );
	},

	onRender: function() {
		this.ui.fieldContainer.sortable( { axis: 'y' } );

		this.toggleMinRowsClass();
	},

	onSortStart: function( event, ui ) {
		ui.item.data( 'oldIndex', ui.item.index() );
	},

	onSortUpdate: function( event, ui ) {
		var oldIndex = ui.item.data( 'oldIndex' ),
			model = this.collection.at( oldIndex ),
			newIndex = ui.item.index();

		this.collection.remove( model );
		this.collection.add( model, { at: newIndex } );
	},

	onAddChild: function() {
		this.updateChildIndexes();
		this.updateActiveRow();
	},

	onRemoveChild: function( childView ) {
		if ( childView === this.currentEditableChild ) {
			delete this.currentEditableChild;
		}

		this.updateChildIndexes();
		this.updateActiveRow();
	},

	onCollectionChanged: function() {
		this.elementSettingsModel.trigger( 'change' );

		this.toggleMinRowsClass();
	},

	onButtonAddRowClick: function() {
		var defaults = {};
		_.each( this.model.get( 'fields' ), function( field ) {
			defaults[ field.name ] = field['default'];
		} );

		var newModel = this.collection.add( defaults ),
			newChildView = this.children.findByModel( newModel );

		this.editRow( newChildView );
	},

	onChildviewClickRemove: function( childView ) {
		childView.model.destroy();
	},

	onChildviewClickDuplicate: function( childView ) {
		this.collection.add( childView.model.clone(), { at: childView.itemIndex } );
	},

	onChildviewClickEdit: function( childView ) {
		this.editRow( childView );
	}
} );

module.exports = ControlRepeaterItemView;

},{"rb-views/controls/base":73,"rb-views/controls/repeater-row":83}],85:[function(require,module,exports){
var ControlBaseItemView = require( 'rb-views/controls/base' ),
	ControlSectionItemView;

ControlSectionItemView = ControlBaseItemView.extend( {
	ui: function() {
		var ui = ControlBaseItemView.prototype.ui.apply( this, arguments );

		ui.heading = '.rb-panel-heading';

		return ui;
	},

	triggers: {
		'click': 'control:section:clicked'
	}
} );

module.exports = ControlSectionItemView;

},{"rb-views/controls/base":73}],86:[function(require,module,exports){
// Attention: DO NOT use this control since it has bugs
// TODO: This control is unused
var ControlBaseItemView = require( 'rb-views/controls/base' ),
	ControlSelectSortItemView;

ControlSelectSortItemView = ControlBaseItemView.extend( {
	ui: function() {
		var ui = ControlBaseItemView.prototype.ui.apply( this, arguments );

		ui.select = '.rb-select-sort';
		ui.selectedOptions = '.rb-select-sort-selector';
		ui.selectedPreview = '.rb-control-selected-preview';
		ui.buttonAdd = '.rb-value-add';
		ui.buttonRemove = '.rb-selected-value-remove';

		return ui;
	},

	childEvents: {
		'click @ui.buttonRemove': 'onClickRemove',
		'click @ui.buttonAdd': 'onClickAdd',
	},

	onReady: function() {

		var self = this;

		this.ui.selectedPreview.sortable( {
			axis: 'y',
			stop: function( event, ui ) {

				var $selectBox = $(self.ui.select).empty();

				$.map($(this).find('.rb-selected-value-preview'), function(el) {
					$selectBox.append('<option value="' + $(el).data('value-id') + '" selected>'+ $(el).data('value-text') +'</option>');
				});

				$selectBox.trigger('change');
			}
		} );

	},

	onClickRemove: function(domEvent) {

		var $element = $(domEvent.currentTarget);
		var id = $element.data('value-id');
		$element.parents('.rb-selected-value-preview').first().remove();
		$(this.ui.select).find('option[value=' + id +' ]').remove().prop("selected", false).appendTo(this.ui.selectedOptions);

		$(this.ui.select).trigger('change');

	},

	onClickAdd: function(domEvent) {

		var self = this;

		$(this.ui.selectedOptions).find(':selected').each(function() {
			$( this ).remove().appendTo(self.ui.select);
			var id = $(this).val();
			var text = $(this).text();

			$(self.ui.selectedPreview).append('<div class="rb-selected-value-preview" data-value-text="' + text + '" data-value-id="' + id  + '"><div class="rb-repeater-row-handle-sortable"><i class="fa fa-ellipsis-v"></i></div>' +
				'<div class="selected-value-preview-info">'
				+ text
				+ '<button data-value-id="' + id + '" data-value-text="' + text + '" class="rb-selected-value-remove selected-value-remove' + id + '"><i class="fa fa-remove"></i></button></div></div>');
		});

		$(this.ui.select).trigger('change');


	},

	onBeforeDestroy: function() {

		this.$el.remove();
	}
} );

module.exports = ControlSelectSortItemView;

},{"rb-views/controls/base":73}],87:[function(require,module,exports){
// Attention: DO NOT use this control since it has bugs
// TODO: This control is unused
var ControlBaseItemView = require( 'rb-views/controls/base' ),
	ControlSelect2ItemView;

ControlSelect2ItemView = ControlBaseItemView.extend( {
	ui: function() {
		var ui = ControlBaseItemView.prototype.ui.apply( this, arguments );

		ui.select = '.rb-select2';

		return ui;
	},

	onReady: function() {
		var options = {
			allowClear: true
		};

		this.ui.select.select2( options );
	},

	onBeforeDestroy: function() {
		if ( this.ui.select.data( 'select2' ) ) {
			this.ui.select.select2( 'destroy' );
		}
		this.$el.remove();
	}
} );

module.exports = ControlSelect2ItemView;

},{"rb-views/controls/base":73}],88:[function(require,module,exports){
var ControlBaseUnitsItemView = require( 'rb-views/controls/base-units' ),
	ControlSliderItemView;

ControlSliderItemView = ControlBaseUnitsItemView.extend( {
	ui: function() {
		var ui = ControlBaseUnitsItemView.prototype.ui.apply( this, arguments );

		ui.slider = '.rb-slider';

		return ui;
	},

	childEvents: {
		'slide @ui.slider': 'onSlideChange'
	},

	initSlider: function() {
		var size = this.getControlValue( 'size' ),
			unitRange = this.getCurrentRange();

		this.ui.input.attr( unitRange ).val( size );

		this.ui.slider.slider( _.extend( {}, unitRange, { value: size } ) );
	},

	resetSize: function() {
		this.setValue( 'size', '' );

		this.initSlider();
	},

	onReady: function() {
		this.initSlider();
	},

	onSlideChange: function( event, ui ) {
		this.setValue( 'size', ui.value );

		this.ui.input.val( ui.value );
	},

	onInputChange: function( event ) {
		var dataChanged = event.currentTarget.dataset.setting;

		if ( 'size' === dataChanged ) {
			this.ui.slider.slider( 'value', this.getControlValue( 'size' ) );
		} else if ( 'unit' === dataChanged ) {
			this.resetSize();
		}
	},

	onBeforeDestroy: function() {
		this.ui.slider.slider( 'destroy' );
		this.$el.remove();
	}
} );

module.exports = ControlSliderItemView;

},{"rb-views/controls/base-units":72}],89:[function(require,module,exports){
var ControlBaseItemView = require( 'rb-views/controls/base' ),
	ControlStructureItemView;

ControlStructureItemView = ControlBaseItemView.extend( {
	ui: function() {
		var ui = ControlBaseItemView.prototype.ui.apply( this, arguments );

		ui.resetStructure = '.rb-control-structure-reset';

		return ui;
	},

	childEvents: {
		'click @ui.resetStructure': 'onResetStructureClick'
	},

	templateHelpers: function() {
		var helpers = ControlBaseItemView.prototype.templateHelpers.apply( this, arguments );

		helpers.getMorePresets = _.bind( this.getMorePresets, this );

		return helpers;
	},

	getCurrentEditedSection: function() {
		var live = rb.getPanelView().getCurrentPageView();

		return live.getOption( 'editedElementView' );
	},

	getMorePresets: function() {
		var parsedStructure = rb.presetsFactory.getParsedStructure( this.getControlValue() );

		return rb.presetsFactory.getPresets( parsedStructure.columnsCount );
	},

	onInputChange: function() {
		this.getCurrentEditedSection().redefineLayout();

		this.render();
	},

	onResetStructureClick: function() {
		this.getCurrentEditedSection().resetColumnsCustomSize();
	}
} );

module.exports = ControlStructureItemView;

},{"rb-views/controls/base":73}],90:[function(require,module,exports){
var ControlMultipleBaseItemView = require( 'rb-views/controls/base-multiple' ),
	ControlUrlItemView;

ControlUrlItemView = ControlMultipleBaseItemView.extend( {
	ui: function() {
		var ui = ControlMultipleBaseItemView.prototype.ui.apply( this, arguments );

		ui.btnExternal = 'button.rb-control-url-target';
		ui.frameOpeners = '.rb-control-url-media';

		return ui;
	},

	// Override the base events
	childEvents: {
		'click @ui.btnExternal': 'onExternalClicked',
		'click @ui.frameOpeners': 'openFrame',
	},

	onReady: function() {
		if ( this.getControlValue( 'is_external' ) ) {
			this.ui.btnExternal.addClass( 'active' );
		}
	},

	openFrame: function() {
		openPsFileManager('rb-control-url-field-' + this.model.cid);
	},

	onExternalClicked: function( event ) {
		event.preventDefault();

		this.ui.btnExternal.toggleClass( 'active' );
		this.setValue( 'is_external', this.isExternal() );
	},

	isExternal: function() {
		return this.ui.btnExternal.hasClass( 'active' );
	}
} );

module.exports = ControlUrlItemView;

},{"rb-views/controls/base-multiple":71}],91:[function(require,module,exports){
var ControlBaseItemView = require( 'rb-views/controls/base' ),
	ControlWPWidgetItemView;

ControlWPWidgetItemView = ControlBaseItemView.extend( {
	ui: function() {
		var ui = ControlBaseItemView.prototype.ui.apply( this, arguments );

		ui.form = 'form';
		ui.loading = '.ps-widget-form-loading';

		return ui;
	},

	events: {
		'keyup @ui.form :input': 'onFormChanged',
		'change @ui.form :input': 'onFormChanged'
	},

	onFormChanged: function() {
		var idBase = 'widget-' + this.model.get( 'id_base' ),
			settings = this.ui.form.rbSerializeObject()[ idBase ].REPLACE_TO_ID;

		this.setValue( settings );
	},

	onReady: function() {}
});

module.exports = ControlWPWidgetItemView;

},{"rb-views/controls/base":73}],92:[function(require,module,exports){
var ControlBaseItemView = require( 'rb-views/controls/base' ),
	ControlWysiwygItemView;

ControlWysiwygItemView = ControlBaseItemView.extend( {

	childEvents: {
		'keyup textarea.rb-wp-live': 'updateElementModel'
	},

	initialize: function() {
		ControlBaseItemView.prototype.initialize.apply( this, arguments );
		this.editorID = 'rbwplive' + this.cid;

	},
	
	attachElContent: function() {
		var liveTemplate = rb.config.wp_live.replace( /rbwplive/g, this.editorID ).replace( '%%liveCONTENT%%', this.getControlValue());
		this.$el.html(liveTemplate);
		return this;
	},

	onShow: function() {
		tinymce.EditorManager.execCommand('mceAddEditor', false, this.editorID);
	},

	onBeforeDestroy: function() {
		tinymce.EditorManager.execCommand('mceRemoveEditor', true, this.editorID);
	}
});

module.exports = ControlWysiwygItemView;

},{"rb-views/controls/base":73}],93:[function(require,module,exports){
var ElementEmptyView;

ElementEmptyView = Marionette.ItemView.extend( {
	template: '#dd-rb-empty-preview',

	className: 'rb-empty-view',

	events: {
		'click': 'onClickAdd'
	},

	onClickAdd: function() {
		rb.getPanelView().setPage( 'elements' );
	}
} );

module.exports = ElementEmptyView;

},{}],94:[function(require,module,exports){
var BaseElementView = require('rb-views/base-element'),
	ColumnView = require('rb-views/column'),
	SectionView;

SectionView = BaseElementView.extend( {
	template: Marionette.TemplateCache.get('#dd-rb-element-section-content'),

	childView: ColumnView,

	className: function() {
		var classes = 'rb-section',
			type = this.isInner() ? 'inner' : 'top';

		classes += ' rb-' + type + '-section';

		return classes;
	},

	tagName: 'section',

	childViewContainer: '> .rb-container > .rb-row',

	triggers: {
		'click .rb-live-section-settings-list .rb-live-element-edit': 'click:edit',
		'click .rb-live-section-settings-list .rb-live-element-trigger': 'click:edit',
		'click .rb-live-section-settings-list .rb-live-element-duplicate': 'click:duplicate'
	},

	elementEvents: {
		'click .rb-live-section-settings-list .rb-live-element-remove': 'onClickRemove',
		'click .rb-live-section-settings-list .rb-live-element-save': 'onClickSave'
	},

	behaviors: {
		Sortable: {
			behaviorClass: require( 'rb-behaviors/sortable' ),
			elChildType: 'column'
		},
		HandleDuplicate: {
			behaviorClass: require( 'rb-behaviors/handle-duplicate' )
		},
		Handlelive: {
			behaviorClass: require( 'rb-behaviors/handle-live' )
		},
		HandleEditMode: {
			behaviorClass: require( 'rb-behaviors/handle-edit-mode' )
		},
		HandleAddMode: {
			behaviorClass: require( 'rb-behaviors/duplicate' )
		},
		HandleElementsRelation: {
			behaviorClass: require( 'rb-behaviors/elements-relation' )
		}
	},

	initialize: function() {
		BaseElementView.prototype.initialize.apply( this, arguments );

		this.listenTo( this.collection, 'add remove reset', this._checkIsFull );
		this.listenTo( this.collection, 'remove', this.onCollectionRemove );
		this.listenTo( this.model, 'change:settings:structure', this.onStructureChanged );
	},

	addEmptyColumn: function() {
		this.addChildModel( {
			id: rb.helpers.getUniqueID(),
			elType: 'column',
			settings: {},
			elements: []
		} );
	},

	addChildModel: function( model, options ) {
		var isModelInstance = model instanceof Backbone.Model,
			isInner = this.isInner();

		if ( isModelInstance ) {
			model.set( 'isInner', isInner );
		} else {
			model.isInner = isInner;
		}

		return BaseElementView.prototype.addChildModel.apply( this, arguments );
	},

	getSortableOptions: function() {
		var sectionConnectClass = this.isInner() ? '.rb-inner-section' : '.rb-top-section';

		return {
			connectWith: sectionConnectClass + ' > .rb-container > .rb-row',
			handle: '> .rb-element-overlay .rb-live-column-settings-list .rb-live-element-trigger',
			items: '> .rb-column'
		};
	},

	getColumnPercentSize: function( element, size ) {
		return size / element.parent().width() * 100;
	},

	getDefaultStructure: function() {
		return this.collection.length + '0';
	},

	getStructure: function() {
		return this.model.getSetting( 'structure' );
	},

	setStructure: function( structure ) {
		var parsedStructure = rb.presetsFactory.getParsedStructure( structure );

		if ( +parsedStructure.columnsCount !== this.collection.length ) {
			throw new TypeError( 'The provided structure doesn\'t match the columns count.' );
		}

		this.model.setSetting( 'structure', structure, true );
	},

	redefineLayout: function() {
		var preset = rb.presetsFactory.getPresetByStructure( this.getStructure() );

		this.collection.each( function( model, index ) {
			model.setSetting( '_column_size', preset.preset[ index ] );
			model.setSetting( '_inline_size', null );
		} );

		this.children.invoke( 'changeSizeUI' );
	},

	resetLayout: function() {
		this.setStructure( this.getDefaultStructure() );
	},

	resetColumnsCustomSize: function() {
		this.collection.each( function( model ) {
			model.setSetting( '_inline_size', null );
		} );

		this.children.invoke( 'changeSizeUI' );
	},

	isCollectionFilled: function() {
		var MAX_SIZE = 10,
			columnsCount = this.collection.length;

		return ( MAX_SIZE <= columnsCount );
	},

	_checkIsFull: function() {
		this.$el.toggleClass( 'rb-section-filled', this.isCollectionFilled() );
	},

	_checkIsEmpty: function() {
		if ( ! this.collection.length ) {
			this.addEmptyColumn();
		}
	},

	getNextColumn: function( columnView ) {
		var modelIndex = this.collection.indexOf( columnView.model ),
			nextModel = this.collection.at( modelIndex + 1 );

		return this.children.findByModelCid( nextModel.cid );
	},

	onBeforeRender: function() {
		this._checkIsEmpty();
	},

	onRender: function() {
		this._checkIsFull();
	},

	onAddChild: function() {
		if (!this.isBuffering) {
			this.resetLayout();
		}
	},

	onCollectionRemove: function() {
		// If it's the last column, please create new one.
		this._checkIsEmpty();

		this.resetLayout();
	},

	onChildviewRequestResizeStart: function( childView ) {
		var nextChildView = this.getNextColumn( childView );

		if ( ! nextChildView ) {
			return;
		}

		var $iframes = childView.$el.find( 'iframe' ).add( nextChildView.$el.find( 'iframe' ) );

		rb.helpers.disableElementEvents( $iframes );
	},

	onChildviewRequestResizeStop: function( childView ) {
		var nextChildView = this.getNextColumn( childView );

		if ( ! nextChildView ) {
			return;
		}

		var $iframes = childView.$el.find( 'iframe' ).add( nextChildView.$el.find( 'iframe' ) );

		rb.helpers.enableElementEvents( $iframes );
	},

	onChildviewRequestResize: function( childView, ui ) {
		// Get current column details
		var currentSize = childView.model.getSetting( '_inline_size' );

		if ( ! currentSize ) {
			currentSize = this.getColumnPercentSize( ui.element, ui.originalSize.width );
		}

		var newSize = this.getColumnPercentSize( ui.element, ui.size.width ),
			difference = newSize - currentSize;

		ui.element.css({
			width: '',
			left: 'initial' // Fix for RTL resizing
		});

		var nextChildView = this.getNextColumn( childView );

		if ( ! nextChildView ) {
			return;
		}

		var MINIMUM_COLUMN_SIZE = 10,

			$nextElement = nextChildView.$el,
			nextElementCurrentSize = this.getColumnPercentSize( $nextElement, $nextElement.width() ),
			nextElementNewSize = nextElementCurrentSize - difference;

		if ( newSize < MINIMUM_COLUMN_SIZE || newSize > 100 || ! difference || nextElementNewSize < MINIMUM_COLUMN_SIZE || nextElementNewSize > 100 ) {
			return;
		}

		// Set the current column size
		childView.model.setSetting( '_inline_size', newSize.toFixed( 3 ) );
		childView.changeSizeUI();

		// Set the next column size
		nextChildView.model.setSetting( '_inline_size', nextElementNewSize.toFixed( 3 ) );
		nextChildView.changeSizeUI();
	},

	onStructureChanged: function() {
		this.redefineLayout();
	},

	onClickSave: function( event ) {
		event.preventDefault();
		var sectionID = this.model.get('id');

		rb.templates.startModal( function() {
			rb.templates.getLayout().showSaveTemplateView(sectionID``);
		} );
	}
} );

module.exports = SectionView;

},{"rb-behaviors/duplicate":1,"rb-behaviors/elements-relation":2,"rb-behaviors/handle-duplicate":3,"rb-behaviors/handle-edit-mode":4,"rb-behaviors/handle-live":5,"rb-behaviors/sortable":7,"rb-views/base-element":67,"rb-views/column":68}],95:[function(require,module,exports){
var SectionView = require( 'rb-views/section' ),
	SectionsCollectionView;

SectionsCollectionView = Marionette.CompositeView.extend( {
	template: Marionette.TemplateCache.get( '#dd-rb-preview' ),

	id: 'rb-inner',

	childViewContainer: '#rb-section-wrap',

	childView: SectionView,

	ui: {
		addSectionArea: '#rb-add-section',
		addNewSection: '#rb-add-new-section',
		closePresetsIcon: '#rb-select-preset-close',
		addSectionButton: '#rb-add-section-button',
		addTemplateButton: '#rb-add-template-button',
		selectPreset: '#rb-select-preset',
		presets: '.rb-preset'
	},

	events: {
		'click @ui.addSectionButton': 'onAddSectionButtonClick',
		'click @ui.addTemplateButton': 'onAddTemplateButtonClick',
		'click @ui.closePresetsIcon': 'closeSelectPresets',
		'click @ui.presets': 'onPresetSelected'
	},

	behaviors: {
		Sortable: {
			behaviorClass: require( 'rb-behaviors/sortable' ),
			elChildType: 'section'
		},
		HandleDuplicate: {
			behaviorClass: require( 'rb-behaviors/handle-duplicate' )
		},
		HandleAdd: {
			behaviorClass: require( 'rb-behaviors/duplicate' )
		},
		HandleElementsRelation: {
			behaviorClass: require( 'rb-behaviors/elements-relation' )
		}
	},

	getSortableOptions: function() {
		return {
			handle: '> .rb-container > .rb-row > .rb-column > .rb-element-overlay .rb-live-section-settings-list .rb-live-element-trigger',
			items: '> .rb-section'
		};
	},

	getChildType: function() {
		return [ 'section' ];
	},

	isCollectionFilled: function() {
		return false;
	},

	initialize: function() {
		this
			.listenTo( this.collection, 'add remove reset', this.onCollectionChanged )
			.listenTo( rb.channels.panelElements, 'element:drag:start', this.onPanelElementDragStart )
			.listenTo( rb.channels.panelElements, 'element:drag:end', this.onPanelElementDragEnd );
	},

	addChildModel: function( model, options ) {
		return this.collection.add( model, options, true );
	},

	addSection: function( properties ) {
		var newSection = {
			id: rb.helpers.getUniqueID(),
			elType: 'section',
			settings: {},
			elements: []
		};

		if ( properties ) {
			_.extend( newSection, properties );
		}

		var newModel = this.addChildModel( newSection );

		return this.children.findByModelCid( newModel.cid );
	},

	closeSelectPresets: function() {
		this.ui.addNewSection.show();
		this.ui.selectPreset.hide();
	},

	fixBlankPageOffset: function() {
		var sectionHandleHeight = 27,
			elTopOffset = this.$el.offset().top,
			elTopOffsetRange = sectionHandleHeight - elTopOffset;

		if ( 0 < elTopOffsetRange ) {
			var $style = Backbone.$( '<style>' ).text( '.rb-live-active #rb-inner{margin-top: ' + elTopOffsetRange + 'px}' );

			rb.$previewContents.children().children( 'head' ).append( $style );
		}
	},

	onAddSectionButtonClick: function() {
		this.ui.addNewSection.hide();
		this.ui.selectPreset.show();
	},

	onAddTemplateButtonClick: function() {
		rb.templates.startModal( function() {
			rb.templates.showTemplates();
		} );
	},

	onRender: function() {
		var self = this;

		self.ui.addSectionArea.html5Droppable( {
			axis: [ 'vertical' ],
			groups: [ 'rb-element' ],
			onDragEnter: function( side ) {
				self.ui.addSectionArea.attr( 'data-side', side );
			},
			onDragLeave: function() {
				self.ui.addSectionArea.removeAttr( 'data-side' );
			},
			onDropping: function() {
				var elementView = rb.channels.panelElements.request( 'element:selected' ),
					newSection = self.addSection(),
					elType = elementView.model.get( 'elType' );

				var elementData = {
					id: rb.helpers.getUniqueID(),
					elType: elType
				};

				if ( 'widget' === elType ) {
					elementData.widgetType = elementView.model.get( 'widgetType' );
				} else {
					elementData.elements = [];
					elementData.isInner = true;
				}

				newSection.triggerMethod( 'request:add', elementData );
			}
		} );

		_.defer( _.bind( self.fixBlankPageOffset, this ) );
	},

	onCollectionChanged: function() {
		rb.setFlagliveChange( true );
	},

	onPresetSelected: function( event ) {
		this.closeSelectPresets();

		var selectedStructure = event.currentTarget.dataset.structure,
			parsedStructure = rb.presetsFactory.getParsedStructure( selectedStructure ),
			elements = [],
			loopIndex;

		for ( loopIndex = 0; loopIndex < parsedStructure.columnsCount; loopIndex++ ) {
			elements.push( {
				id: rb.helpers.getUniqueID(),
				elType: 'column',
				settings: {},
				elements: []
			} );
		}

		var newSection = this.addSection( { elements: elements } );

		newSection.setStructure( selectedStructure );
		newSection.redefineLayout();
	},

	onPanelElementDragStart: function() {
		rb.helpers.disableElementEvents( this.$el.find( 'iframe' ) );
	},

	onPanelElementDragEnd: function() {
		rb.helpers.enableElementEvents( this.$el.find( 'iframe' ) );
	}
} );

module.exports = SectionsCollectionView;

},{"rb-behaviors/duplicate":1,"rb-behaviors/elements-relation":2,"rb-behaviors/handle-duplicate":3,"rb-behaviors/sortable":7,"rb-views/section":94}],96:[function(require,module,exports){
var BaseElementView = require( 'rb-views/base-element' ),
	WidgetView;

WidgetView = BaseElementView.extend( {
	_templateType: null,

	getTemplate: function() {
		if ( 'remote' !== this.getTemplateType() ) {
			return Marionette.TemplateCache.get( '#dd-rb-' + this.model.get( 'elType' ) + '-' + this.model.get( 'widgetType' ) + '-content' );
		} else {
			return _.template( '' );
		}
	},

	className: function() {
		return 'rb-widget rb-widget-' + this.model.get( 'widgetType' );
	},

	modelEvents: {
		'before:remote:render': 'onModelBeforeRemoteRender',
		'remote:render': 'onModelRemoteRender'
	},

	triggers: {
		'click': {
			event: 'click:edit',
			stopPropagation: false
		},
		'click > .rb-live-element-settings .rb-live-add-element': 'click:add',
		'click > .rb-live-element-settings .rb-live-element-duplicate': 'click:duplicate'
	},

	elementEvents: {
		'click > .rb-live-element-settings .rb-live-element-remove': 'onClickRemove'
	},

	behaviors: {
		Handlelive: {
			behaviorClass: require( 'rb-behaviors/handle-live' )
		},
		HandleEditMode: {
			behaviorClass: require( 'rb-behaviors/handle-edit-mode' )
		}
	},

	initialize: function() {
		BaseElementView.prototype.initialize.apply( this, arguments );

		if ( ! this.model.getHtmlCache() ) {
			this.model.renderRemoteServer();
		}
	},

	getTemplateType: function() {
		if (null === this._templateType ) {
			var $template = Backbone.$( '#dd-rb-' + this.model.get( 'elType' ) + '-' + this.model.get( 'widgetType' ) + '-content' );

			if ( 0 === $template.length ) {
				this._templateType = 'remote';
			} else {
				this._templateType = 'js';
			}
		}

		return this._templateType;
	},

	onModelBeforeRemoteRender: function() {
		//this.$el.addClass( 'rb-loading' );
	},

	onBeforeDestroy: function() {
		rb.$previewContents.find( '#rb-style-' + this.model.cid ).remove();
	},

	onModelRemoteRender: function() {
		if (this.isDestroyed) {
			return;
		}

		this.$el.removeClass('rb-loading');
		this.render();
	},

	attachElContent: function( html ) {
		var htmlCache = this.model.getHtmlCache();
		if ( htmlCache ) {
			html = htmlCache;
		}
		_.defer( _.bind( function() {
			rbFrontend.getScopeWindow().jQuery( '#' + this.getElementUniqueClass() ).html( html );
		}, this ) );

		return this;
	},

	onRender: function() {
		var self = this;

		self.$el
			.removeClass( 'rb-widget-empty' )
			.children( '.rb-widget-empty-icon' )
			.remove();

		self.$el.imagesLoaded().always( function() {
			setTimeout( function() {
				if (self.model.getIcon() == 'instagram') {
					self.$el.append(self.model.onHtml(rb_instagram));
					self.$el.append(html.replace('rb_change', 'instagram'));
				}
				
				if ( 1 > self.$el.height() ) {
					self.$el.addClass('rb-widget-empty');
					
					if (self.model.getIcon() == 'rb-text') {
						self.$el.append(self.model.onHtml(text_editor));
						self.$el.append(html.replace('rb_change', 'text-editor'));
					}

					if (self.model.getIcon() == 'brand') {
						self.$el.append(self.model.onHtml(brand));
						self.$el.append(html.replace('rb_change', 'brand'));
					}

					if (self.model.getIcon() == 'product') {
						self.$el.append(self.model.onHtml(product));
						self.$el.append(html.replace('rb_change', 'product'));
					}

					if (self.model.getIcon() == 'product-tab') {
						self.$el.append(self.model.onHtml(product_tab));
						self.$el.append(html.replace('rb_change', 'product-tab'));
					}

					if (self.model.getIcon() == 'image-carousel') {
						self.$el.append(self.model.onHtml(image_carousel));
						self.$el.append(html.replace('rb_change', 'image-carousel'));
					}

					if (self.model.getIcon() == 'menu') {
						self.$el.append(self.model.onHtml(menu));
						self.$el.append(html.replace('rb_change', 'menu'));
					}

					if (self.model.getIcon() == 'newsletter') {
						self.$el.append(self.model.onHtml(newsletter));
						self.$el.append(html.replace('rb_change', 'newsletter'));
					}

					if (self.model.getIcon() == 'blog') {
						self.$el.append(self.model.onHtml(blog));
						self.$el.append(html.replace('rb_change', 'blog'));
					}

					if (self.model.getIcon() == 'module') {
						self.$el.append(self.model.onHtml(rb_module));
						self.$el.append(html.replace('rb_change', 'module'));
					}

					if (self.model.getIcon() == 'tpl') {
						self.$el.append(self.model.onHtml(rb_tpl));
						self.$el.append(html.replace('rb_change', 'tpl'));
					}

					if (self.model.getIcon() == 'map') {
						self.$el.append(self.model.onHtml(map));
						self.$el.append(html.replace('rb_change', 'map'));
					}

					if (self.model.getIcon() == 'accordion') {
						self.$el.append(self.model.onHtml(accordion));
						self.$el.append(html.replace('rb_change', 'accordion'));
					}

					if (self.model.getIcon() == 'testimonial') {
						self.$el.append(self.model.onHtml(testimonial));
						self.$el.append(html.replace('rb_change', 'testimonial'));
					}

					if (self.model.getIcon() == 'video') {
						self.$el.append(self.model.onHtml(video));
						self.$el.append(html.replace('rb_change', 'video'));
					}
				}
			}, 200 );
		} );
	}
} );

module.exports = WidgetView;

},{"rb-behaviors/handle-edit-mode":4,"rb-behaviors/handle-live":5,"rb-views/base-element":67}]},{},[61,62,27])