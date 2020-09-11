<section class="container-fluid books-list mainwrapper">
<div class="row">
<?php for($b=0; $b<count($books); $b++){
		$book_thumb = &$books[$b]['book_thumb'];
		$file = &$books[$b]['book_link'];
	?>
	<div class="col-lg-2 col-md-3 col-6">
		<a href="index.php?action=pdf&type=book&file=<?=$file?>" target='_blank' class="book-thumb d-flex align-items-end" style="background: url('<?=$book_thumb?>');">
			
		</a>
	</div>
<?php } ?>
	
</div>
</section>
<?php include('javascript.php') ?>