


function add_person() {
  save_method = 'add';
  $('#form')[0].reset(); // reset form on modals
  $('.form-group').removeClass('has-error'); // clear error class
  $('.help-block').empty(); // clear error string
  $('#modal_form').modal('show'); // show bootstrap modal
  $('.modal-title').text('Add Person'); // Set Title to Bootstrap modal title

  $('#photo-preview').hide(); // hide photo preview modal

  $('#label-photo').text('Upload Photo'); // label photo upload
}

function edit_person(id) {
  save_method = 'update';
  $('#form')[0].reset(); // reset form on modals
  $('.form-group').removeClass('has-error'); // clear error class
  $('.help-block').empty(); // clear error string


  //Ajax Load data from ajax
  $.ajax({
    url: "<?php echo site_url( 'person/ajax_edit' )?>/" + id,
    type: "GET",
    dataType: "JSON",
    success: function (data) {

      $('[name="id"]').val(data.id);
      $('[name="first_name"]').val(data.first_name);
      $('[name="last_name"]').val(data.last_name);
      $('[name="gender"]').val(data.gender);
      $('[name="address"]').val(data.address);
      $('[name="dob"]').datepicker('update', data.dob);
      $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
      $('.modal-title').text('Edit Person'); // Set title to Bootstrap modal title

      $('#photo-preview').show(); // show photo preview modal

      if (data.photo) {
        $('#label-photo').text('Change Photo'); // label photo upload
        $('#photo-preview div').html('<img src="' + base_url + 'upload/' + data.photo + '" class="img-responsive">'); // show photo
        $('#photo-preview div').append('<input type="checkbox" name="remove_photo" value="' + data.photo + '"/> Remove photo when saving'); // remove photo

      }
      else {
        $('#label-photo').text('Upload Photo'); // label photo upload
        $('#photo-preview div').text('(No photo)');
      }


    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert('Error get data from ajax');
    }
  });
}

function reload_table() {
  table.ajax.reload(null, false); //reload datatable ajax
}

function save() {
  $('#btnSave').text('saving...'); //change button text
  $('#btnSave').attr('disabled', true); //set button disable
  var url;

  if (save_method == 'add') {
    url = "<?php echo site_url( 'person/ajax_add' )?>";
  } else {
    url = "<?php echo site_url( 'person/ajax_update' )?>";
  }

  // ajax adding data to database

  var formData = new FormData($('#form')[0]);
  $.ajax({
    url: url,
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    dataType: "JSON",
    success: function (data) {

      if (data.status) //if success close modal and reload ajax table
      {
        $('#modal_form').modal('hide');
        reload_table();
      }
      else {
        for (var i = 0; i < data.inputerror.length; i++) {
          $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
          $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); //select span help-block class set text error string
        }
      }
      $('#btnSave').text('save'); //change button text
      $('#btnSave').attr('disabled', false); //set button enable


    },
    error: function (jqXHR, textStatus, errorThrown) {
      alert('Error adding / update data');
      $('#btnSave').text('save'); //change button text
      $('#btnSave').attr('disabled', false); //set button enable

    }
  });
}

function delete_person(id) {
  if (confirm('Are you sure delete this data?')) {
    // ajax delete data to database
    $.ajax({
      url: "<?php echo site_url( 'person/ajax_delete' )?>/" + id,
      type: "POST",
      dataType: "JSON",
      success: function (data) {
        //if success reload ajax table
        $('#modal_form').modal('hide');
        reload_table();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        alert('Error deleting data');
      }
    });

  }
}