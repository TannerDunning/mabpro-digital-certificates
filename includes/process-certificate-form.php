<?php
function process_certificate_form() {
    require_once plugin_dir_path( __FILE__ ) . '../libs/TCPDF/tcpdf.php';
    if ( isset( $_POST['submit_certificate_form'] ) ) {
        // Verify the nonce for security
        if ( ! isset( $_POST['mabpro_certificate_form_nonce'] ) || ! wp_verify_nonce( $_POST['mabpro_certificate_form_nonce'], 'mabpro_certificate_form' ) ) {
            wp_die( 'Nonce verification failed.' );
        }
// Get the instructor's user ID and token balance
$instructor_id = get_current_user_id();
$cert_balance = get_user_meta( $instructor_id, 'cert_balance', true );
// Check if the instructor has enough tokens to send the certificates
      if (is_null($cert_balance)) {
    $cert_balance = 0;
}
// Sanitize and validate the form data
$course_date = sanitize_text_field( $_POST['course_date'] );
if ( empty( $course_date ) ) {
    wp_die( 'Course date is required.' );
}

// Process the student information
$students = array();
foreach ( $_POST['students'] as $student_data ) {
    $student = array();

    // Sanitize and validate student data
    $student['first_name'] = sanitize_text_field( $student_data['first_name'] );
    if ( empty( $student['first_name'] ) ) {
        wp_die( 'Student first name is required.' );
    }

    $student['last_name'] = sanitize_text_field( $student_data['last_name'] );
    if ( empty( $student['last_name'] ) ) {
        wp_die( 'Student last name is required.' );
    }

    $student['email'] = sanitize_email( $student_data['email'] );
    if ( ! is_email( $student['email'] ) ) {
        wp_die( 'Invalid student email address.' );
    }

    $students[] = $student;
}

// Check if the instructor has enough tokens to send certificates for all students
if ( count( $students ) > $cert_balance ) {
    wp_die( 'You do not have enough tokens to send certificates for all students.' );
}
// Deduct the tokens from the instructor's balance
$cert_balance -= count( $students );
update_user_meta( $instructor_id, 'cert_balance', $cert_balance );

        $class_type = sanitize_text_field( $_POST['class_type'] );
        if ( empty( $class_type ) ) {
            wp_die( 'Class type is required.' );
        }

            // Get the last certificate ID from the most recent certificate post
    $last_certificate_id = 0;
    $args = array(
        'post_type' => 'mabpro_certificates',
        'posts_per_page' => 1,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        $query->the_post();
        $last_certificate_id = (int) get_post_meta(get_the_ID(), 'certificate_id', true);
    }
    wp_reset_postdata();


        // Generate PDF for the current student
        generate_certificate_pdf($student, $course_date, $class_type, $last_certificate_id);

        // Store the generated certificate data in the database
        // This is now done inside the generate_certificate_pdf() function
    }

    // Continue with the next steps (token management, etc.)
}

// Update the generate_certificate_pdf() function to accept the certificate ID parameter
function generate_certificate_pdf( $student, $course_date, $class_type, $certificate_id ) {
    // Create a new PDF document
    $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('MABPRO');
    $pdf->SetTitle('Certificate');

    // Remove header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Add a page
    $pdf->AddPage();

    // Set the certificate background image based on class type
    $background_image = '';
    switch ( $class_type ) {
        case 'M1 MAB':
            $background_image = plugin_dir_path( __FILE__ ) . '../assets/certificate-background-m1-mab.jpg';
            break;
        case 'M2 MAB':
            $background_image = plugin_dir_path( __FILE__ ) . '../assets/certificate-background-m2-mab.jpg';
            break;
        case 'CPR':
            $background_image = plugin_dir_path( __FILE__ ) . '../assets/certificate-background-cpr.jpg';
            break;
        // Add more cases for additional class types
    }

    // Set the background image
    $pdf->Image($background_image, 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight(), '', '', '', false, 300, '', false, false, 0);

    // Set font
    $pdf->SetFont('times', '', 20);

    // Content
    $html = '
    <div style="position: absolute; left: 268mm; top: 144mm; text-align: center; font-size: 24px; font-weight: bold;">' . $student['first_name'] . ' ' . $student['last_name'] . '</div>
    <div style="position: absolute; left: 89mm; top: 310mm; text-align: center; font-size: 18px; font-weight: bold;">Course Date: ' . $course_date . '</div>
    <div style="position: absolute; left: 139.34mm; top: 109.132mm; text-align: center; font-size: 18px; font-weight: bold;">Certificate ID: ' . $certificate_id . '</div>
    ';

    // Write content
    $pdf->writeHTML($html, true, false, true, false, '');
    return $pdf->Output('', 'S');





    
    // Save the certificate information
$post_id = wp_insert_post(array(
    'post_title'    => $student['first_name'] . ' ' . $student['last_name'] . ' - ' . $class_type . ' - ' . $course_date,
    'post_content'  => '',
    'post_status'   => 'publish',
    'post_author'   => 1,
    'post_type'     => 'mabpro_certificates',
));

// Add custom fields to the new post
update_post_meta($post_id, 'first_name', $student['first_name']);
update_post_meta($post_id, 'last_name', $student['last_name']);
update_post_meta($post_id, 'class_type', $class_type);
update_post_meta($post_id, 'course_date', $course_date);
update_post_meta($post_id, 'certificate_id', $certificate_id);
update_post_meta($post_id, 'email', $student['email']);


}

function process_verification_form() {
    if ( isset( $_POST['action'] ) && 'verify_certificate' === $_POST['action'] ) {
        // Verify the nonce for security
        if ( ! isset( $_POST['mabpro_verify_certificate_nonce'] ) || ! wp_verify_nonce( $_POST['mabpro_verify_certificate_nonce'], 'mabpro_verify_certificate' ) ) {
            wp_die( 'Nonce verification failed.' );
        }

        // Sanitize and validate the form data
        $certificate_id = (int) sanitize_text_field( $_POST['certificate_id'] );
        if ( empty( $certificate_id ) ) {
            wp_die( 'Certificate ID is required.' );
        }

        // Search for the certificate using WP_Query
        $args = array(
            'post_type' => 'mabpro_certificates',
            'meta_query' => array(
                array(
                    'key' => 'certificate_id',
                    'value' => $certificate_id,
                    'compare' => '=',
                ),
            ),
            'posts_per_page' => 1,
        );

        $query = new WP_Query( $args );

        // Display the verification message
        if ( $query->have_posts() ) {
            $query->the_post();
            $first_name = get_post_meta( get_the_ID(), 'first_name', true );
            $last_name = get_post_meta( get_the_ID(), 'last_name', true );
            $class_type = get_post_meta( get_the_ID(), 'class_type', true );
            $course_date = get_post_meta( get_the_ID(), 'course_date', true );

            $message = sprintf(
                'Certificate #%d is valid. The student %s %s has successfully completed the course %s on %s.',
                $certificate_id,
                $first_name,
                $last_name,
                $class_type,
                $course_date
            );
        } else {
            $message = 'Certificate not found. Please check the Certificate ID and try again.';
        }

        // Reset the query
        wp_reset_postdata();

        // Redirect to the verification form with the verification message
        $redirect_url = add_query_arg( array( 'message' => urlencode( $message ) ), wp_get_referer() );
        wp_safe_redirect( $redirect_url );
        exit;
    }
}

function display_verification_form( $atts ) {
    ob_start();
    require_once plugin_dir_path( __FILE__ ) . 'verification-form.php';
    return ob_get_clean();
}
function send_certificate_emails($students, $course_date, $class_type) {
    // Get the last certificate ID from the most recent certificate post
    $last_certificate_id = get_last_certificate_id();

    // Generate and send certificates to students
    foreach ($students as $student) {
        // Increment the certificate ID
        $last_certificate_id++;

        // Generate PDF for the current student
        generate_certificate_pdf($student, $course_date, $class_type, $last_certificate_id);
    }

    // Send a single email to the instructor and office with all certificates attached
    send_email_to_instructor_and_office($students, $course_date, $class_type);
}

function get_last_certificate_id() {
    $last_certificate_id = 0;
    $args = array(
        'post_type' => 'mabpro_certificates',
        'posts_per_page' => 1,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        $query->the_post();
        $last_certificate_id = (int) get_post_meta(get_the_ID(), 'certificate_id', true);
    }
    wp_reset_postdata();
    return $last_certificate_id;
}

function send_email_to_instructor_and_office($students, $course_date, $class_type) {
    // Get the current user's email
    $current_user = wp_get_current_user();
    $instructor_email = $current_user->user_email;

    // Prepare the email
    $to = $instructor_email;
    $subject = 'Certificates for ' . $class_type . ' on ' . $course_date;
    $message = 'Dear Instructor,<br><br>Attached are the certificates for the ' . $class_type . ' class held on ' . $course_date . '.<br><br>Best regards,<br>MABPRO Team';
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $attachments = generate_certificates_attachments($students, $course_date, $class_type);

    // Send the email to the instructor
    wp_mail($to, $subject, $message, $headers, $attachments);

    // Send a copy to the office
    $to_office = 'office@mabpro.com';
    wp_mail($to_office, $subject, $message, $headers, $attachments);

    // Delete the temporary files
    foreach ($attachments as $attachment) {
        unlink($attachment);
    }
}

function generate_certificates_attachments($students, $course_date, $class_type) {
    $attachments = array();
    foreach ($students as $student) {
        // Get the certificate ID
        $certificate_id = get_certificate_id_by_student_data($student, $course_date, $class_type);
        
        // Generate the certificate PDF
        $pdf_string = generate_certificate_pdf($student, $course_date, $class_type, $certificate_id);

        // Create a temporary file to store the PDF content
        $tmp_file = wp_tempnam();
        file_put_contents($tmp_file, $pdf_string);

        // Add the temporary file to the attachments array
        $attachments[] = $tmp_file;
    }
    return $attachments;
}

function get_certificate_id_by_student_data($student, $course_date, $class_type) {
    $certificate_id = 0;
$args = array(
'post_type' => 'mabpro_certificates',
'meta_query' => array(
'relation' => 'AND',
array(
'key' => 'first_name',
'value' => $student['first_name'],
'compare' => '=',
),
array(
'key' => 'last_name',
'value' => $student['last_name'],
'compare' => '=',
),
array(
'key' => 'email',
'value' => $student['email'],
'compare' => '=',
),
array(
'key' => 'class_type',
'value' => $class_type,
'compare' => '=',
),
array(
'key' => 'course_date',
'value' => $course_date,
'compare' => '=',
),
),
'posts_per_page' => 1,
);
$query = new WP_Query($args);
if ($query->have_posts()) {
    $query->the_post();
    $certificate_id = (int) get_post_meta(get_the_ID(), 'certificate_id', true);
}
wp_reset_postdata();
return $certificate_id;
}
add_shortcode( 'mabpro_verification_form', 'display_verification_form' );
add_action( 'admin_post_submit_certificate_form', 'process_certificate_form' );
add_action( 'admin_post_nopriv_submit_certificate_form', 'process_certificate_form' );
