<form id="mabpro_verification_form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
    <input type="hidden" name="action" value="verify_certificate">
    <?php wp_nonce_field( 'mabpro_verify_certificate', 'mabpro_verify_certificate_nonce' ); ?>

    <div>
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" id="first_name" required>
    </div>

    <div>
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" id="last_name" required>
    </div>

    <div>
        <label for="certificate_id">Certificate ID:</label>
        <input type="text" name="certificate_id" id="certificate_id" required>
    </div>

    <div>
    <label for="select_an_industry">Please select your industry:</label>
    <select name="select_an_industry" id="select_an_industry" required>
        <option value="">Please select</option>
        <option value="caregiving">Caregiving</option>
        <option value="licensed_healthcare">Licensed Healthcare</option>
        <option value="other">Other</option>
    </select>
</div>

    <button type="submit">Verify Certificate</button>
</form>