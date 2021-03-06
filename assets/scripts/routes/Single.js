/* eslint-disable no-console */
// const urlVariable = (variable) =>
// {
//     const query = window.location.search.substring(1);
//     const vars = query.split("&");
//     for (let i=0;i<vars.length;i++) {
//         let pair = vars[i].split("=");
//         if(pair[0] == variable){return pair[1];}
//     }
//     return(false);
// };

export default {
  init() {

      /**
       * Commenting out automatic scrolling to section start
       *
       * Just comment off, if needed in future...
       */
    // if(urlVariable('section') !== false) {
    //   const mainSelector = $('main');
    //   $('html,body').animate({scrollTop: mainSelector.offset().top}, 'fast');
    // }

    const tabs = $('#tabNav li').get();
    const navLinks = $('.nav-link').get();

    tabs.forEach(value => {
      $(value).removeClass('active');
    });

    const activeTab = tabs.filter(
      value =>
        $(value)
          .children('a')
          .data('target') == $.urlParam('section')
    );

    activeTab.length > 0
      ? $(activeTab).addClass('active')
      : $(tabs[0]).addClass('active');

    navLinks.forEach(value => {
      $(value).click(e => {
        tabs.forEach(value => {
          e.target.href === value.href && $(value).click();
        });
      });
    });

    gallerize('#natureGallery', '.location__nature');
    gallerize('#historyGallery', '.location__history');

    $('a[data-toggle="tab"]').on('shown.bs.tab', () => {
      window.WPLeafletMapPlugin.maps.forEach(value => {
        value._leaflet_id !== 2 && value.setZoom(15);
        value.invalidateSize();
      });
    });

    $('.species-wrapper .link-component').matchHeight();

    const tabNav = $('#tabNav');
    const tabNavOffset = tabNav.offset().top + 30;

    $(window).scroll(e => {
      const scroll = $(e.target).scrollTop();

      if (scroll >= tabNavOffset) {
        if (!tabNav.hasClass('fixed-tabs')) tabNav.addClass('fixed-tabs');
      } else if (scroll < tabNavOffset) {
        if (tabNav.hasClass('fixed-tabs')) tabNav.removeClass('fixed-tabs');
      }
    });
  },
  finalize() {},
};

function gallerize(galleryId, locationTab) {
  $(galleryId).slick({
    centerMode: true,
    slidesToShow: 1,
    variableWidth: true,
    dots: true,
    initialSlide: 0,
    adaptiveHeight: true,
  });

  const introPar = $(`${locationTab} .content--center .text-content p`)[0];

  $(galleryId).insertAfter(introPar);
}
