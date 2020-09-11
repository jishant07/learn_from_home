
      <section class="container-fluid watchlist mainwrapper">
        <div class="row">
            <?php for($w=0; $w<count($wlist); $w++) {
					$wl = & $wlist[$w];
					$cid = $wl['cid'];
					$vid = $wl['vid'];
					$vthumb = $wl['vthumb'];
					$href="index.php?action=history-videos&cid=$cid&id=$vid";
			?>
			<div class="col-lg-3 col-md-3 col-6">
                  <a href="<?=$href?>" class="video-thumb d-flex align-items-end" style="background: url('<?=$vthumb?>');" title="<?=$wl['title']?>">
                    <div class="content">
                      <h1><?=$wl['title']?></h1>
                      <div class="text">
                      <?=getSubject($wl['subject'])?>
                      </div>
                      <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  </a>
            </div>
            <?php } ?>
        </div>
      </section>
    </div>
    <?php include('javascript.php') ?>