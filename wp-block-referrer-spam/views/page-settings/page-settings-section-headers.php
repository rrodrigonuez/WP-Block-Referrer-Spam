<p>
	<?php echo __( 'Referrer Spam list will be updated automatically every week, based on the <a href="https://github.com/piwik/referrer-spam-blacklist" target="_blank" title="Community-contributed list of referrer spammers"><strong>Community-contributed list of referrer spammers</strong></a>, maintained by <a href="http://piwik.org/" target="_blank" title="Piwik">Piwik</a>' ); ?>.
</p>
<?php if ( $n_referrers ) {
	echo '<p>' . __( 'Currently this plugin is bloking' ) . ' <strong><span id="referrer_count">' . $n_referrers . '</span></strong> ' . _( 'referrer spammer(s)' ) . '.</p>';
} ?>
<p>
	<?php echo __( 'You can also add custom Referrer Spammer URLs using the box below' ); ?>.
</p>