<div id="slideshow">
  <!-- start -->
  <div id="foobar">
    <div id="col1"><a href="#" class="previous">&nbsp;</a></div>
    <div id="col2">
      <div class="viewer">
        <div class="reel">
          <div class="slide"><img src="images/img04.jpg" width="726" height="335" alt="" /> <span>Lorem Ipsum Dolor Sit Amet.</span> </div>
          <div class="slide"><img src="images/img07.jpg" width="726" height="335" alt="" /> <span>Mauris vitae nisl nec metus placerat consectetuer.</span> </div>
          <div class="slide"><img src="images/img08.jpg" width="726" height="335" alt="" /> <span>Nam bibendum dadn nulla tortor elementum ipsum</span> </div>
        </div>
      </div>
    </div>
    <div id="col3"><a href="#" class="next">&nbsp;</a></div>
  </div>
  <script type="text/javascript">

  $('#foobar').slidertron({
    viewerSelector      : '.viewer',
    reelSelector        : '.viewer .reel',
    slidesSelector      : '.viewer .reel .slide',
    navPreviousSelector : '.previous',
    navNextSelector     : '.next',
    navFirstSelector    : '.first',
    navLastSelector     : '.last'
  });

  </script>
  <!-- end -->
</div>
