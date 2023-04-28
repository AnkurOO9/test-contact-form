$(function () {
  jQuery.validator.addMethod(
    "lettersonly",
    function (value, element) {
      return this.optional(element) || /^[a-z\s]+$/i.test(value);
    },
    "Only alphabetical characters"
  );

  $("#contact-form").validate({
    rules: {
      name: {
        required: true,
        lettersonly: true,
        minlength: 2,
        maxlength: 50,
      },
      email: {
        required: true,
        email: true,
      },
      phone: {
        required: true,
        digits: true,
        minlength: 10,
        maxlength: 12,
      },
      message: "required",
    },
    messages: {
      name: {
        required: "Please enter your name",
        minlength: "Name must be at least 2 characters long.",
        maxlength: "Name can be at most 50 characters long.",
      },
      email: {
        required: "Please enter your email address",
        email: "Please enter a valid email address",
      },
      phone: {
        required: "Please enter your phone number",
        minlength: "Please enter a valid phone number",
        maxlength: "Please enter a valid phone number",
      },
      message: "Please enter a message",
    },
    submitHandler: function (form) {
      $("#preloder").fadeIn();

      $.ajax({
        type: "POST",
        url: "submit.php",
        data: $(form).serialize(),
        success: function (response) {
          $("#preloder").fadeOut();
          Swal.fire({
            icon: "success",
            title: "Thank you!",
            text: "",
            html: "Your message has been sent.",
          });
          form.reset();
        },
        error: function (response) {
          Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Something went wrong. Please try again later.",
          });
        },
      });
    },
  });
});
