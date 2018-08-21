<?hh
  function buildCarousel() {
    $userImagesrc = userImageSrc();
    return(
      <div id="Carousel" class="tab-pane fade">
        <div class="panel panel-info">
          <div class="panel-heading">
            <p class="text-warning">Carousel</p>
          </div>
          <div class="panel-body">
            <div id="myCarousel" class="carousel slide" data-ride="carousel">
              <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
                <li data-target="#myCarousel" data-slide-to="2"></li>
              </ol>

              <div class="carousel-inner" role="listbox">
                <div class="item active">
                  <img class="img-responsive center-block" src="../../public/images/messagerLog.png" alt="Messager logo"/>
                  <div class="carousel-caption">
                    <h3>Logo</h3>
                    <p>This is logo I desgined</p>
                  </div>
                </div>

                <div class="item">
                  <img class="img-responsive center-block" src="../../public/images/ys.jpg" alt="background"/>
                  <div class="carousel-caption">
                    <h3>Background</h3>
                    <p>This is background of site</p>
                  </div>
                </div>

                <div class="item">
                  <img class="img-responsive center-block" src={$userImagesrc} alt="User picture"/>
                  <div class="carousel-caption">
                    <h3>User picture</h3>
                    <p>This is picture which user has upload or not</p>
                  </div>
                </div>
              </div>

              <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
              </a>
              <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
              </a>
              </div>
          </div>
        </div>
      </div>
    );
  }
