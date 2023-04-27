<form id="mabpro_verification_form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
    <input type="hidden" name="action" value="verify_certificate">
    <?php wp_nonce_field( 'mabpro_verify_certificate', 'mabpro_verify_certificate_nonce' ); ?>
    <div>
        <label for="certificate_id">Certificate ID:</label>
        <input type="text" id="certificate_id" name="certificate_id" required>
    </div>
    <button type="submit">Verify Certificate</button>
</form>
