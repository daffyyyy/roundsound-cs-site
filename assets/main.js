$(document).ready(function () {
  var maxGroup = 10;

  //add more fields group
  $("#youtube-add-more").click(function () {
    if ($("body").find("#input-group-youtube").length < maxGroup) {
      var fieldHTML =
        '<div id="input-group-youtube" class="input-group">' +
        $("#input-group-youtube-copy").html() +
        "</div>";
      $("body").find("#input-group-youtube:last").after(fieldHTML);
    } else {
      alert("Maximum " + maxGroup + " groups are allowed.");
    }
  });

  //remove fields group
  $("body").on("click", "#youtube-remove-more", function () {
    $(this).parents("#input-group-youtube").remove();
  });

  $("#youtube-start-generate").click(function () {
      var youtube_url = $("#youtube-url-1").val();
      
      if (youtube_url.length > 10) {
        $("#youtube-info").show();
      }
    let form = $("#youtube-form");
    $.ajax({
      type: "POST",
      url: "upload.php?yt",
      data: form.serialize(), // serializes the form's elements.
      success: function (data) {
        console.log(data);
        if (!data.includes("Error")) setTimeout(showLink, 3500);
        $("#youtube-info").hide();
      },
    });
  });

  function showLink() {
    $("#download").show();
  }

  var previewNode = document.querySelector("#template");
  previewNode.id = "";
  var previewTemplate = previewNode.parentNode.innerHTML;
  previewNode.parentNode.removeChild(previewNode);

  let myDropzone = new Dropzone(document.body, {
    // Make the whole body a dropzone
    url: "upload.php", // Set the url
    acceptedFiles: ".mp3",
    maxFiles: 10,
    maxSize: 8,
    previewTemplate: previewTemplate,
    autoQueue: false, // Make sure the files aren't queued until manually added
    previewsContainer: "#previews", // Define the container to display the previews
    clickable: ".fileinput-button", // Define the element that should be used as click trigger to select files.
  });

  myDropzone.on("addedfile", function (file) {
    // Hookup the start button
    // file.previewElement.querySelector(".start").onclick = function() {
    //     myDropzone.enqueueFile(file);
    // };
  });

  // Update the total progress bar
  myDropzone.on("totaluploadprogress", function (progress) {
    // document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
  });

  myDropzone.on("sending", function (file, xhr, formData) {
    title = file.previewElement.querySelector("input[name='title']").value;
    time = file.previewElement.querySelector("input[name='time']").value;
    // session_id = file.previewElement.querySelector("input[name='title']").value;
    formData.append("title", title);
    formData.append("time", time);
    formData.append("pack_id", $("#pack_id").val());
    // formData.append("pack_id", '<?php echo $pack_id; ?>');
    // Show the total progress bar when upload starts
    document.querySelector("#total-progress").style.opacity = "1";
    // And disable the start button
  });

  // Hide the total progress bar when nothing's uploading anymore
  myDropzone.on("queuecomplete", function (progress) {
    document.querySelector("#total-progress").style.opacity = "0";
    setTimeout(showLink, 3500);
  });

  // Setup the buttons for all transfers
  // The "add files" button doesn't need to be setup because the config
  // `clickable` has already been specified.
  document.querySelector("#actions .start").onclick = function () {
    myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
  };
  document.querySelector("#actions .cancel").onclick = function () {
    myDropzone.removeAllFiles(true);
  };
});
