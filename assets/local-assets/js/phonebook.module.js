/*
 * Handles crud traffic from the client-side.
 */

var personsCrudHandler = function() {

  var crudMethod;

  this.addPerson = function() {
    debugger;
    crudMethod = 'add';
    $('#form')[0].reset(); // reset modals
    $('.form-group').removeClass('has-error'); // clr errors
    $('.help-block').empty(); // clear notice
    $('#modal_form').modal('show'); // wo-hoo
    $('.modal-title').text('Add Person'); // modal title
    $('#photo-preview').hide(); // hide photo prev
    $('#label-photo').text('Upload Photo'); // photo up

  };

  this.editPerson = function(id) {

    debugger;
    crudMethod = 'update';
    $('#form')[0].reset(); // reset modals
    $('.form-group').removeClass('has-error'); // clr errors
    $('.help-block').empty(); // clear notice
    console.log('id');
    $.ajax({
          url: siteUrl + 'person/ajax_edit/' + id,
          type: 'GET',
          dataType: 'JSON',
          success: function(data) {

            $('[name="id"]').val(data.id);
            $('[name="first_name"]').val(data.first_name);
            $('[name="last_name"]').val(data.last_name);
            $('[name="gender"]').val(data.gender);
            $('[name="address"]').val(data.address);
            $('[name="dob"]').datepicker('update', data.dob);
            $('#modal_form').modal('show'); // wait for everyone to get aboard
            $('.modal-title').text('Edit Person');

            $('#photo-preview').show(); // lift off

            if (data.photo) {
              $('#label-photo').text('Change Photo'); // label photo upload
              $('#photo-preview div').
                  html('<img src="' + base_url + 'upload/' + data.photo +
                      '" class="img-responsive">'); // show photo
              $('#photo-preview div').
                  append('<input type="checkbox" name="remove_photo" value="' +
                      data.photo + '"/> Remove photo when saving'); // remove photo
            }
            else {
              $('#label-photo').text('Upload Photo'); // label photo upload
              $('#photo-preview div').text('(No photo)');
            }

          },
          error: function() {
            debugger;
            console.log('editPerson');
          }
        }
    );
  };


  this.save = function(){

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


  }




};






var save_method; //for save method string
var table;


$(document).ready(function () {

  //datatables
  table = $('#table').DataTable({

    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "order": [], //Initial no order.

    // Load data for the table's content from an Ajax source
    "ajax": {
      "url": "<?php echo site_url( 'person/ajax_list' )?>",
      "type": "POST"
    },

    //Set column definition initialisation properties.
    "columnDefs": [
      {
        "targets": [-1], //last column
        "orderable": false, //set not orderable
      },
      {
        "targets": [-2], //2 last column (photo)
        "orderable": false, //set not orderable
      },
    ],

  });

  //datepicker

  $('.datepicker').datepicker({
    autoclose: true,
    format: "yyyy-mm-dd",
    todayHighlight: true,
    orientation: "top auto",
    todayBtn: true,
    todayHighlight: true,
  });

  //set input/textarea/select event when change value, remove class error and remove text help block
  $("input").change(function () {
    $(this).parent().parent().removeClass('has-error');
    $(this).next().empty();
  });
  $("textarea").change(function () {
    $(this).parent().parent().removeClass('has-error');
    $(this).next().empty();
  });
  $("select").change(function () {
    $(this).parent().parent().removeClass('has-error');
    $(this).next().empty();
  });




function edit_person(id) {

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