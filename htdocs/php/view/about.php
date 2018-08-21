<?hh
  function aboutData() {
    return (
      <div id="aboutMenu" class="tab-pane fade">
        <div class="panel panel-info">
          <div class="panel-heading">
            <p class="text-warning">About Project</p> 
          </div>
          <div class="panel-body">
            <div class="container">
              <div class="row">
                <div class="col-xs-6 col-md-3">
                  <p class="lead">Simple way to stay connected</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-offset-2">
                  <img class="imgAbout" src="../../public/images/messagerLog.png" alt="Messager logo"/>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-1">
                  <p>1.0.0</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }