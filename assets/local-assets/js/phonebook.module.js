/*
 * Handles crud traffic from and off the client-side.
 */

var crudMethod, table; // init globs
//console.log(baseUrl);
$(document).ready(function() {

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

    $.ajax({
          url: baseUrl + 'person/ajax_edit/' + id,
          type: 'GET',
          dataType: 'JSON',
          success: function(data) {

            $('[name="id"]').val(data.id);
            $('[name="first_name"]').val(data.first_name);
            $('[name="last_name"]').val(data.last_name);
            $('[name="gender"]').val(data.gender);
            $('[name="address"]').val(data.address);
            $('[name="dob"]').on("dp.change", function (e) {

              data.dob = new Date(e.date);
              $('.datetimepicker').data("DateTimePicker").date(data.dob);
            });
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
            console.log('ERR ->editPerson');
          },
        }
    );
  };

  this.save = function() {

    var url;
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled', true); //set button disable

    if (crudMethod === 'add') {
      url = baseUrl + 'person/ajax_add';
    }
    if (crudMethod === 'update') {
      url = baseUrl + 'person/ajax_update';
    }

    var formData = new FormData($('#form')[0]);
    $.ajax({
      url: url,
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      dataType: 'JSON',
      success: function(data) {

        if (data.status) //if success close modal and reload ajax table
        {
          $('#modal_form').modal('hide');
          this.reloadTable();
        }
        else {
          for (var i = 0; i < data.inputerror.length; i++) {
            $('[name="' + data.inputerror[i] + '"]').
                parent().
                parent().
                addClass('has-error'); //select parent twice to select div form-group class and add has-error class
            $('[name="' + data.inputerror[i] + '"]').
                next().
                text(data.error_string[i]); //select span help-block class set text error string
          }
        }
        $('#btnSave').text('save');
        $('#btnSave').attr('disabled', false);

      },
      error: function() {

        debugger;
        console.log('ERR -> save');
        $('#btnSave').text('save'); //change button text
        $('#btnSave').attr('disabled', false); //set button enable

      },
    });
  };



  this.deletePerson = function() {

    if (confirm('Are you sure to delete this data?')) {
      $.ajax({
        url: baseUrl + 'person/ajax_delete/' + id,
        type: 'POST',
        dataType: 'JSON',
        success: function(data) {
          $('#modal_form').modal('hide');
          this.reloadTable();
        },
        error: function() {
          debugger;
          console.log('ERR -> reloadTable');
        },
      });

    }
  };

  table = $('#table').DataTable({

    'processing': true, //Feature control the processing indicator.
    'serverSide': true, //Feature control DataTables' server-side processing mode.
    'order': [], //Initial no order.

    // Load data for the table's content from an Ajax source
    'ajax': {
      'url': baseUrl + 'person/ajax_list',
      'type': 'POST',
    },

    //Set column definition initialisation properties.
    'columnDefs': [
      {
        'targets': [-1], //last column
        'orderable': false, //set not orderable
      },
      {
        'targets': [-2], //2 last column (photo)
        'orderable': false, //set not orderable
      },
    ],

  });

  this.reloadTable = function() {
    table.ajax.reload(null, false); //reload datatable ajax
  };

  //datepicker

  $('.datetimepicker').datetimepicker({
    format: 'YYYY-MM-DD',
  });

  //set input/textarea/select event when change value, remove class error and remove text help block
  $('input').change(function() {
    $(this).parent().parent().removeClass('has-error');
    $(this).next().empty();
  });
  $('textarea').change(function() {
    $(this).parent().parent().removeClass('has-error');
    $(this).next().empty();
  });
  $('select').change(function() {
    $(this).parent().parent().removeClass('has-error');
    $(this).next().empty();
  });

});


