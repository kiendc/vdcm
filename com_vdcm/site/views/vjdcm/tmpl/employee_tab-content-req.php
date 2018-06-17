<div class="container-fluid">
    <div class="row">
        <div class="col-sm-11 col-md-11">
            <!-- Split button -->
            <button type="button" class="btn btn-default" data-toggle="tooltip" title="Refresh">
                &nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-refresh"></span>&nbsp;&nbsp;&nbsp;</button>
            <!-- Single button -->
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <?php echo JText::_( 'VJEECDCM_RV_BT_UPDATE_REQUESTS' ); ?>&nbsp;<span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Mark all as read</a></li>
                    <li class="divider"></li>
                    <li class="text-center"><small class="text-muted">Select messages to see more actions</small></li>
                </ul>
            </div>
            <div class="pull-right">
                <span class="text-muted"><b>1</b>–<b>50</b> of <b>160</b></span>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-default">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                    </button>
                    <button type="button" class="btn btn-default">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                <li class="active"><a href="#home" data-toggle="tab"><span class="glyphicon glyphicon-inbox"></span>&nbsp;
                    <?php echo JText::_( 'VJEECDCM_RV_SDMN_RECEIVED' ); ?></a></li>
                <li><a href="#profile" data-toggle="tab"><span class="glyphicon glyphicon-user"></span>&nbsp;
                    <?php echo JText::_( 'VJEECDCM_RV_SDMN_PROCESSING' ); ?></a></li>
                <li><a href="#messages" data-toggle="tab"><span class="glyphicon glyphicon-tags"></span>&nbsp;
                    <?php echo JText::_( 'VJEECDCM_RV_SDMN_COMPLETED' ); ?></a></li>
                <li><a href="#settings" data-toggle="tab"><span class="glyphicon glyphicon-plus no-margin"></span>&nbsp;
                    <?php echo JText::_( 'VJEECDCM_RV_SDMN_ARCHIVED' ); ?></a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane fade in active" id="home">
                    <?php
                        echo $this->loadTemplate('tab-content-recv-req');
                        ?>
                </div>
                <div class="tab-pane fade in" id="profile">
                    <?php
                        echo $this->loadTemplate('tab-content-proc-req');
                        ?>
                </div>
                <div class="tab-pane fade in" id="messages">
                    <?php
                        echo $this->loadTemplate('tab-content-compl-req');
                        ?>
                </div>
                <div class="tab-pane fade in" id="settings">
                    <?php
                        echo $this->loadTemplate('tab-content-arch-req');
                        ?>
                </div>
            </div>
            
        </div>
    </div>
</div>
