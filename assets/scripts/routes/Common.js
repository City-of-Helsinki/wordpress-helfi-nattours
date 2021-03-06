export default {
  init() {
    $('body').show();
    const leftOpen = $('#leftOpen');
    const leftMenu = $('#leftMenu');
    const leftClose = $('#leftClose');

    leftOpen.click(() => {
      leftMenu.css('transform', 'translateX(0)');
      leftMenu.css('overflow', 'auto');
      $('body').css('overflow', 'hidden');
    });
    leftClose.click(() => {
      leftMenu.css('transform', 'translateX(-100%)');
      leftMenu.css('overflow', 'hidden');
      $('body').css('overflow', 'auto');
    });

    const rightOpen = $('#rightOpen');
    const rightMenu = $('#rightMenu');
    const rightClose = $('#rightClose');

    rightOpen.click(() => {
      rightMenu.css('transform', 'translateX(0)');
      rightMenu.css('overflow', 'auto');
      $('body').css('overflow', 'hidden');
    });
    rightClose.click(() => {
      rightMenu.css('transform', 'translateX(100%)');
      rightMenu.css('overflow', 'hidden');
      $('body').css('overflow', 'auto');
    });

      /**
       * Prevent right click on img
       */
      $('img').bind('contextmenu',function() { return false; });

    $('#locationGallery').slick({
      arrows: false,
      centerMode: true,
      slidesToShow: 2,
      variableWidth: false,
      dots: false,
      initialSlide: 1,
      centerPadding: 0,
    });

    $.urlParam = function(name) {
      var results = new RegExp('[?&]' + name + '=([^&#]*)').exec(
        window.location.href
      );
      // console.log(name, results); //eslint-disable-line
      return results ? results[1] : 0;
    };

    $(window).on('load', () => {
      popupTTS();

      const maps = window.WPLeafletMapPlugin.maps;
      // let circle;
      // let interval;

      maps.length > 0 &&
        maps.forEach(value => {
          value.addControl(new window.L.Control.Fullscreen());
          value.addControl(
            new window.L.control.locate({
              flyTo: true,
              cacheLocation: true,
              locateOptions: {
                enableHighAccuracy: true,
              },
            })
          );
          // value.on('fullscreenchange', () => {
          //   if (value.isFullscreen()) {
          //     value.locate();

          //     value.on('locationfound', e => {
          //       circle = window.L.circleMarker(e.latlng, { radius: 5 });
          //       circle.addTo(value);
          //     });

          //     interval = setInterval(() => {
          //       value.locate({
          //         watch: true,
          //       });

          //       value.on('locationfound', e => {
          //         circle.removeFrom(value).setLatLng(e.latlng);
          //       });
          //     }, 5000);
          //   } else {
          //     clearInterval(interval);
          //   }
          // });
        });
    });

    $('.img-caption')
      .prev()
      .mouseenter(e => {
        $(e.currentTarget)
          .next('.img-caption')
          .css('color', 'rgba(0, 0, 0, .5)');
      });

    $('.img-caption')
      .prev()
      .mouseleave(e => {
        $(e.currentTarget)
          .next('.img-caption')
          .css('color', 'rgba(0, 0, 0, 0)');
      });
  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },
};

function getResponsiveVoiceLangName() {
    if(document.documentElement.lang === 'fi') {
        return 'Finnish Female';
    } else if(document.documentElement.lang === 'sv-se') {
        return 'Swedish Female';
    } else if(document.documentElement.lang === 'en-us') {
        return 'US English Female';
    }
}

function popupTTS() {
  const markers = window.WPLeafletMapPlugin.markers;

  $(markers).click(e => {
    const contentNode = e.target._popup._contentNode;

    $(contentNode)
      .find('br')
      .remove();

    const innerText = contentNode.innerText.replace(/\.?\r?\n|\r|\n/g, '. ');

    //Only show responsive voice options in Finnish, English or Swedish
      if(document.documentElement.lang === 'fi' || document.documentElement.lang === 'en-us' || document.documentElement.lang === 'sv-se') {

          const responsiveVoiceLng = getResponsiveVoiceLangName();

          $(contentNode).prepend(
              `<span
        class="glyphicon
        glyphicon-volume-up"
        aria-hidden="true"
        style="display: block; font-size: 24px; margin-bottom: 1rem; cursor: pointer"
        onclick="window.responsiveVoice.speak('${innerText}', '${responsiveVoiceLng}');"
        </span>`
          );
      }

    $(e.target._popup).on('remove', () => {
      window.responsiveVoice.cancel();
    });
  });
}
