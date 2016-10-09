<?php
/**
 * Outputs inline JS for the front-end JS composer
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 3.5.0
 */

class VCEX_Inline_JS {

	/**
	 * Class Constructor
	 *
	 * @since 2.0.0
	 */
	public function __construct( $scripts ) {
		$this->output( $scripts ); 
	}

	/**
	 * Output JS
	 *
	 * @since 2.0.0
	 */
	private function output( $scripts ) {

		// Array of scripts
		if ( is_array( $scripts ) ) { ?>
			<?php $this->wpexLocalize(); ?>
			<script type="text/javascript">
				jQuery( function( $ ) {
					<?php
					$this->parseData();
					foreach ( $scripts as $script ) :
						if ( method_exists( $this, $script ) ) {
							$this->$script();
						}
					endforeach; ?>
				} );
			</script>
		<?php }
		
		// Output single script
		elseif ( method_exists( $this, $scripts ) ) { ?>
			<?php $this->wpexLocalize(); ?>
			<script type="text/javascript">
				jQuery( function( $ ) {
					<?php
						$this->parseData();
						$this->$scripts();
					?>
				} );
			</script>
		<?php }

	}

	/**
	 * Load wpexLocalize
	 *
	 * @since 2.0.0
	 */
	private function wpexLocalize() {
		if ( function_exists( 'json_encode' ) ) {
			$localize_array = WPEX_Theme_Setup::localize_array(); ?>
				<script type='text/javascript'>
				/* <![CDATA[ */var wpexLocalize = <?php echo wp_json_encode( $localize_array ); ?> /* ]]> */
				</script>
			<?php
		}

	}

	/**
	 * Parse Data
	 *
	 * @since 2.0.0
	 */
	private function parseData() { ?>
		function wpexParseData( val, fallback ) {
			return ( typeof val !== 'undefined' ) ? val : fallback;
		}
	<?php }

	/**
	 * Isotope
	 *
	 * @since 2.0.0
	 */
	private function isotope() { ?>

		if("undefined"!=$.fn.imagesLoaded&&"undefined"!=$.fn.isotope){var self=this;$(".vcex-isotope-grid").each(function(){var t=$(this);t.imagesLoaded(function(){t.isotope({itemSelector:".vcex-isotope-entry",transformsEnabled:!0,isOriginLeft:wpexLocalize.isRTL?!1:!0,transitionDuration:t.data("transition-duration")?t.data("transition-duration")+"s":"0.4s",layoutMode:t.data("layout-mode")?t.data("layout-mode"):"masonry",filter:t.data("filter")?t.data("filter"):""})});var i=t.prev("ul.vcex-filter-links");if(i.length){var a=i.find("a");a.click(function(){var i=$(this).attr("data-filter");return t.isotope({filter:i}),$(this).parents("ul").find("li").removeClass("active"),$(this).parent("li").addClass("active"),!1})}})}

	<?php }

	/**
	 * Carousels
	 *
	 * @since 2.0.0
	 */
	private function carousel() { ?>

		"undefined"!=$.fn.owlCarousel&&$(".wpex-carousel").each(function(){var e=$(this),a=e.data(),t=wpexLocalize.isRTL?!0:!1,s=a.smartSpeed?a.smartSpeed:wpexLocalize.carouselSpeed;e.owlCarousel({animateIn:!1,animateOut:!1,lazyLoad:!1,smartSpeed:s,rtl:t,dots:a.dots,nav:a.nav,items:a.items,slideBy:a.slideby,center:a.center,loop:a.loop,margin:a.margin,autoplay:a.autoplay,autoplayTimeout:a.autoplayTimeout,navText:['<span class="fa fa-chevron-left"><span>','<span class="fa fa-chevron-right"></span>'],responsive:{0:{items:a.itemsMobilePortrait},480:{items:a.itemsMobileLandscape},768:{items:a.itemsTablet},960:{items:a.items}}})});

	<?php }

	/**
	 * iLightbox Single Image
	 *
	 * @since 2.0.0
	 */
	private function ilightbox_single() { ?>

		if($.fn.iLightBox!=undefined){

			$(".wpex-lightbox").each(function(){var e=$(this);if(!e.hasClass("wpex-lightbox-group-item")){var i=e.data();e.iLightBox({skin:wpexParseData(i.skin,wpexLocalize.iLightbox.skin),controls:{fullscreen:wpexLocalize.iLightbox.controls.fullscreen},show:{title:wpexLocalize.iLightbox.show.title,speed:parseInt(wpexLocalize.iLightbox.show.speed)},hide:{speed:parseInt(wpexLocalize.iLightbox.hide.speed)},effects:{reposition:!0,repositionSpeed:200,switchSpeed:300,loadedFadeSpeed:wpexLocalize.iLightbox.effects.loadedFadeSpeed,fadeSpeed:wpexLocalize.iLightbox.effects.fadeSpeed},overlay:wpexLocalize.iLightbox.overlay,social:wpexLocalize.iLightbox.social})}});

		}

	<?php }

	/**
	 * iLightbox Single Image
	 *
	 * @since 2.0.0
	 */
	private function carousel_lightbox() { ?>

		if($.fn.iLightBox!=undefined){

			$(".wpex-carousel-lightbox").each(function(){var e=$(this),i=e.find(".owl-item"),t=e.find(".wpex-carousel-lightbox-item"),o=e.find(".wpex-carousel-lightbox-item").data(),a=new Array;i.each(function(){if(!$(this).hasClass("cloned")){var e=$(this).find(".wpex-carousel-lightbox-item");e.length>0&&a.push({URL:e.attr("href"),title:e.attr("data-title"),caption:e.attr("data-caption")})}}),a.length>0&&t.on("click",function(e){e.preventDefault();var i=$(this).data("count")-1,i=i?i:0;$.iLightBox(a,{startFrom:parseInt(i),path:"horizontal",infinite:wpexParseData(o.infinite,wpexLocalize.iLightbox.infinite),skin:wpexParseData(o.skin,wpexLocalize.iLightbox.skin),show:{title:wpexParseData(o.show_title,wpexLocalize.iLightbox.show.title),speed:parseInt(wpexLocalize.iLightbox.show.speed)},hide:{speed:parseInt(wpexLocalize.iLightbox.hide.speed)},controls:{arrows:wpexLocalize.iLightbox.controls.arrows,thumbnail:wpexLocalize.iLightbox.controls.thumbnail,fullscreen:wpexLocalize.iLightbox.controls.fullscreen,mousewheel:wpexLocalize.iLightbox.controls.mousewheel},effects:{reposition:!0,repositionSpeed:200,switchSpeed:300,loadedFadeSpeed:wpexLocalize.iLightbox.effects.loadedFadeSpeed,fadeSpeed:wpexLocalize.iLightbox.effects.fadeSpeed},overlay:wpexLocalize.iLightbox.overlay,social:wpexLocalize.iLightbox.social})})});

		}

	<?php }

	/**
	 * iLightbox Auto Detect
	 *
	 * @since 2.0.0
	 */
	private function ilightbox_autodetect() { ?>

		if($.fn.iLightBox!=undefined){

			$(".wpex-lightbox-autodetect, .wpex-lightbox-autodetect a").each(function(){var e=$(this),i=e.data();e.iLightBox({smartRecognition:!0,skin:wpexParseData(i.skin,wpexLocalize.iLightbox.skin),path:"horizontal",controls:{fullscreen:wpexLocalize.iLightbox.controls.fullscreen},show:{title:wpexLocalize.iLightbox.show.title,speed:parseInt(wpexLocalize.iLightbox.show.speed)},hide:{speed:parseInt(wpexLocalize.iLightbox.hide.speed)},effects:{reposition:!0,repositionSpeed:200,switchSpeed:300,loadedFadeSpeed:wpexLocalize.iLightbox.effects.loadedFadeSpeed,fadeSpeed:wpexLocalize.iLightbox.effects.fadeSpeed},overlay:wpexLocalize.iLightbox.overlay,social:wpexLocalize.iLightbox.social})});

		}

	<?php }

	/**
	 * iLightbox
	 *
	 * @since 2.0.0
	 */
	private function ilightbox_custom_gallery() { ?>

		if($.fn.iLightBox!=undefined){

			$(".wpex-lightbox-gallery").on("click",function(){var e=$(this).data("gallery").split(",");return e&&$.iLightBox(e,{skin:wpexLocalize.iLightbox.skin,path:"horizontal",infinite:!0,show:{title:wpexLocalize.iLightbox.show.title,speed:parseInt(wpexLocalize.iLightbox.show.speed)},hide:{speed:parseInt(wpexLocalize.iLightbox.hide.speed)},controls:{arrows:wpexLocalize.iLightbox.controls.arrows,thumbnail:wpexLocalize.iLightbox.controls.thumbnail,fullscreen:wpexLocalize.iLightbox.controls.fullscreen,mousewheel:wpexLocalize.iLightbox.controls.mousewheel},effects:{reposition:!0,repositionSpeed:200,switchSpeed:300,loadedFadeSpeed:wpexLocalize.iLightbox.effects.loadedFadeSpeed,fadeSpeed:wpexLocalize.iLightbox.effects.fadeSpeed},overlay:wpexLocalize.iLightbox.overlay,social:wpexLocalize.iLightbox.social}),!1});

		}

	<?php }


	/**
	 * iLightbox
	 *
	 * @since 2.0.0
	 */
	private function ilightbox() {

		// some bugs that need fixing..do nothing yet.
		return; ?>

	<?php }

	/**
	 * DataHovers
	 *
	 * @since 2.0.0
	 */
	private function data_hover() { ?>

	   $(".wpex-data-hover").each(function(){var o=$(this),t=$(this).css("backgroundColor"),r=$(this).css("color"),s=$(this).attr("data-hover-background"),c=$(this).attr("data-hover-color");o.hover(function(){void 0!=CSSStyleDeclaration.prototype.setProperty?(s&&this.style.setProperty("background-color",s,"important"),c&&this.style.setProperty("color",c,"important")):(s&&o.css("background-color",s),c&&o.css("color",c))},function(){void 0!=CSSStyleDeclaration.prototype.setProperty?(s&&this.style.setProperty("background-color",t,"important"),c&&this.style.setProperty("color",r,"important")):(s&&t&&o.css("background-color",t),c&&r&&o.css("color",r))})});

	<?php }

	/**
	 * Equal Height - Global
	 *
	 * @since 2.0.0
	 */
	private function equal_heights() { ?>
		if ( $.fn.wpexEqualHeights!=undefined ) {
			$(".match-height-grid").wpexEqualHeights({children:".match-height-content"}),$(".match-height-row").wpexEqualHeights({children:".match-height-content"}),$(".vcex-feature-box-match-height").wpexEqualHeights({children:".vcex-match-height"}),$(".blog-entry-equal-heights").wpexEqualHeights({children:".blog-entry-inner"}),$(".wpex-vc-row-columns-match-height").wpexEqualHeights({children:".wpex-vc-column-wrapper"}),$(".wpex-vc-columns-wrap").wpexEqualHeights({children:".equal-height-column"}),$(".wpex-vc-columns-wrap").wpexEqualHeights({children:".equal-height-content"});
		}
	<?php }

	/**
	 * Equal Height Content
	 *
	 * @since 2.0.0
	 */
	private function equal_height_content() { ?>
		if($.fn.wpexEqualHeights!=undefined){$( '.wpex-vc-columns-wrap' ).wpexEqualHeights( { children : '.equal-height-content' } );}
	<?php }

	/**
	 * Equal Height Columns
	 *
	 * @since 2.0.0
	 */
	private function row_equal_columns() { ?>
		if($.fn.wpexEqualHeights!=undefined) {
			$( '.wpex-vc-row-columns-match-height' ).wpexEqualHeights( {
				children : '.wpex-vc-column-wrapper'
			} );
			$( '.wpex-vc-row-reset-columns-match-height' ).find( '.wpex-vc-column-wrapper' ).css( 'height', '' );
		}
	<?php }

	/**
	 * Slider Pro
	 *
	 * @since 2.0.0
	 */
	private function slider_pro() { ?>

		if("undefined"!=$.fn.sliderPro){

			var self=this;$(".wpex-slider").each(function(){var a=$(this),e=a.data();$(".wpex-slider-slide, .wpex-slider-thumbnails").css({opacity:1,display:"block"});var t=$(".wpex-slider").prev(".wpex-slider-preloaderimg"),i=t.length?t.outerHeight():null;a.sliderPro({responsive:!0,width:"100%",height:i,fade:wpexParseData(e.fade,600),touchSwipe:wpexParseData(e.touchSwipe,!0),fadeDuration:wpexParseData(e.animationSpeed,600),slideAnimationDuration:wpexParseData(e.animationSpeed,600),autoHeight:wpexParseData(e.autoHeight,!0),heightAnimationDuration:wpexParseData(e.heightAnimationDuration,500),arrows:wpexParseData(e.arrows,!0),fadeArrows:wpexParseData(e.fadeArrows,!0),autoplay:wpexParseData(e.autoPlay,!0),autoplayDelay:wpexParseData(e.autoPlayDelay,5e3),buttons:wpexParseData(e.buttons,!0),shuffle:wpexParseData(e.shuffle,!1),orientation:wpexParseData(e.direction,"horizontal"),loop:wpexParseData(e.loop,!1),keyboard:!1,fullScreen:wpexParseData(e.fullscreen,!1),slideDistance:wpexParseData(e.slideDistance,0),thumbnailHeight:wpexParseData(e.thumbnailHeight,70),thumbnailWidth:wpexParseData(e.thumbnailWidth,70),thumbnailPointer:wpexParseData(e.thumbnailPointer,!1),updateHash:wpexParseData(e.updateHash,!1),thumbnailArrows:!1,fadeThumbnailArrows:!1,thumbnailTouchSwipe:!0,fadeCaption:wpexParseData(e.fadeCaption,!0),captionFadeDuration:500,waitForLayers:!0,autoScaleLayers:!0,forceSize:"none",thumbnailPosition:"bottom",reachVideoAction:"playVideo",leaveVideoAction:"pauseVideo",endVideoAction:"nextSlide",init:function(){a.prev(".wpex-slider-preloaderimg").hide(),a.parent(".gallery-format-post-slider")&&$(".blog-masonry-grid").length&&setTimeout(function(){$(".blog-masonry-grid").isotope("layout")},$heightAnimationDuration+1)},gotoSlideComplete:function(){a.parent(".gallery-format-post-slider")&&$(".blog-masonry-grid").length&&$(".blog-masonry-grid").isotope("layout")}})}),$(".woo-product-entry-slider").click(function(){return!1});

		}

	<?php }

	/**
	 * Skillbar
	 *
	 * @since 2.0.0
	 */
	private function skillbar() { ?>

		$(".vcex-skillbar").each(function(){$(this).find(".vcex-skillbar-bar").animate({width:$(this).attr("data-percent")},800)});

	<?php }

	/**
	 * Skillbar
	 *
	 * @since 2.0.0
	 */
	private function milestone() { ?>

		if($.fn.appear!=undefined&&$.fn.countTo!=undefined){
		
			$( '.vcex-animated-milestone' ).each( function() {
				$( this ).appear( function() {
					$( this ).find( '.vcex-milestone-time' ).countTo( {
						formatter: function ( value, options ) {
							return value.toFixed(options.decimals).replace(/\B(?=(?:\d{3})+(?!\d))/g, ',');
						},
					} );
				}, {
					accX    : 0,
					accY    : 0
				} );
			} );

		}

	<?php }

	/**
	 * Pop-up title overlay
	 *
	 * @since 2.0.0
	 */
	private function overlay_popup_title() { ?>
		
		$(".overlay-parent-title-push-up").each(function(){var t=$(this),o=t.find(".overlay-title-push-up"),s=t.find("a"),i=s.find("img"),e=o.outerHeight();t.imagesLoaded(function(){o.css({bottom:-e}),s.css({height:i.outerHeight()}),i.css({position:"absolute",top:"0",left:"0",width:"100%",height:"100%"}),t.hover(function(){i.css({top:-20}),o.css({bottom:0})},function(){i.css({top:"0"}),o.css({bottom:-e})})})});

	<?php }

	/**
	 * Parallax
	 *
	 * @since 2.0.0
	 */
	private function parallax() { ?>
		if ( $.fn.scrolly2 != 'undefined' ) {
			$( '.wpex-parallax-bg' ).each( function() {
				var $this = $( this );
				$this.scrolly2().trigger( 'scroll' );
			} );
		}
	<?php }

	/**
	 * Responsive font size
	 *
	 * @since 2.0.0
	 */
	private function responsive_text() { ?>
		if($.fn.flowtype!=undefined){
			$(".wpex-responsive-txt").each(function(){var a=$(this),t=a.data(),e=wpexParseData(t.minFontSize,13),n=wpexParseData(t.maxFontSize,40),o=wpexParseData(t.responsiveTextRatio,10);a.flowtype({fontRatio:o,minFont:e,maxFont:n})});
		}
	<?php }

	/**
	 * Typed Text
	 *
	 * @since 2.0.0
	 */
	private function typed_text() { ?>
		if ( $.fn.typed != undefined ) {
			$( '.vcex-typed-text' ).each( function() {
				var $this     = $( this );
				var $settings = $this.data( 'settings' );
				$settings.typeSpeed = parseInt( $settings.typeSpeed );
				$settings.backDelay = parseInt( $settings.backDelay );
				$settings.backSpeed = parseInt( $settings.backSpeed );
				$settings.startDelay = parseInt( $settings.startDelay );
				$settings.strings = $this.data( 'strings' );
				$this.typed( $settings );
			} );
		}
	<?php }

} // End Class

/**
 * Helper function runs the VCEX_Inline_JS class 
 *
 * @since 2.0.0
 */
function vcex_inline_js( $scripts ) {
	if ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) {
		return new VCEX_Inline_Js( $scripts );
	}
}