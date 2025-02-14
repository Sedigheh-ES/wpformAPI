<div id="form_success" style="background-color:green; color:#fff;"></div>
<div id="form_error" style="background-color:red; color:#fff;"></div>

<form id="enquiry_form">
    <?php wp_nonce_field('wp_rest'); ?>
    <label>Name</label><br />
    <input type="text" name="name" class="text-type" /><br />
    <label>Email</label><br />
    <input type="text" name="email" class="text-type" /><br />
    <label>Phone</label><br />
    <input type="phone" name="phone" class="text-type" /><br />
    <label>Descriptiom</label><br />
    <textarea name="message"></textarea><br />
    <button type="submit" value="submit form" name="submit" id="onsub">Submit </button>
</form>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript">
var nonce = '<?php echo wp_create_nonce('wp_rest'); ?>';

jQuery(document).ready(function($) {

    $("#enquiry_form").submit(function(event) {
        event.preventDefault();

        // $("#form_error").hide();
        var form = $(this)
        console.log('form data:', form);

        $.ajax({
            url: "<?php echo get_rest_url(null,'v1/contact-form/submit'); ?>",
            type: "POST",
            data: form.serialize(),
            success: function() {
                form.hide();
                $("#form_success").html("Your message has been sent").fadeIn();
            },
            error: function() {
                $("#form_error").html("There was an error submitting").fadeIn();
            }
        });
    });
});
</script>