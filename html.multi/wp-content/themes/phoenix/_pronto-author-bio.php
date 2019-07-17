<?php
echo "" ?>
<div class="well">
	<figure class="media team row">
		<?php
		$name = get_the_author_meta( 'display_name' );
		echo get_avatar( get_the_author_meta('email'), $size = '249', $default = 'mm', $alt = $name  );
   ?>
		<div class="clearfix visible-xs"></div>
		<figcaption class="media-body">
			<h3 itemprop="name"><?php echo $name; ?></h3>
			<h4 itemprop="jobTitle"><?php echo get_the_author_meta( 'position' ); ?></h4>
			<div class="social">
				<?php if (get_the_author_meta('facebook')) { ?>
				<a href="<?php echo get_the_author_meta('facebook') ?>" class="fa-stack author-icon-linkedin" target="_blank">
					<i class="fa fa-circle fa-stack-2x"></i>
					<i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
				</a>
				<?php } ?>
				<?php if (get_the_author_meta('twitter')) { ?>
				<a href= "http://twitter.com/<?php echo get_the_author_meta('twitter') ?>" class="fa-stack author-icon-twitter" target="_blank">
					<i class="fa fa-circle fa-stack-2x"></i>
					<i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
				</a>
				<?php } ?>
				<?php if (get_the_author_meta('linkedin_profile')) { ?>
				<a href="http://www.linkedin.com/in/<?php echo get_the_author_meta('linkedin_profile') ?>" class="fa-stack author-icon-linkedin" target="_blank">
					<i class="fa fa-circle fa-stack-2x"></i>
					<i class="fa fa-linkedin fa-stack-1x fa-inverse"></i>
				</a>
				<?php } ?>
				<?php if (get_the_author_meta('googleplus')) { ?>
				<a href="<?php echo get_the_author_meta('googleplus') ?>/" class="fa-stack author-icon-google-plus" target="_blank">
					<i class="fa fa-circle fa-stack-2x"></i>
					<i class="fa fa-google-plus fa-stack-1x fa-inverse"></i>
				</a>
				<?php } ?>
				<?php if (get_the_author_meta('email') && esc_attr( get_the_author_meta( 'author_email' ) == "yes")) { ?>
				<a href="mailto:<?php echo get_the_author_meta('email') ?>" class="fa-stack author-icon-envelope" target="_blank">
					<i class="fa fa-circle fa-stack-2x"></i>
					<i class="fa fa-envelope fa-stack-1x fa-inverse"></i>
				</a>
				<?php } ?>
				<?php if (get_the_author_meta('url')) { ?>
				<a href="<?php echo get_the_author_meta('url') ?>" class="fa-stack author-icon-linkedin" target="_blank">
					<i class="fa fa-circle fa-stack-2x"></i>
					<i class="fa fa-link fa-stack-1x fa-inverse"></i>
				</a>
				<?php } ?>
			</div>
			<p><?php echo get_the_author_meta('description'); ?></p>
		</figcaption>
	</figure>
</div>

<?php "" ?>
