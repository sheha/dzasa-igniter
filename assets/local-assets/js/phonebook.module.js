/*
 * Module that handles crud traffic on and off the client-side,
 * defines datatable, and keeps the DOM alive.
 * author: @sheha
 */
$(document).ready(function() {

  // declare vars at the top, ECMA rules
  var crudMethod, table;



  $(".dropdown-toggle").dropdown();

  this.addPerson = function() {
    // debugger;
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

            // datepicker is a special snowflake
            $('[name="dob"]').on('dp.change', function(e) {
              data.dob = new Date(e.date);
              $('.datetimepicker').data('DateTimePicker').date(data.dob);
            });

            $('#modal_form').modal('show'); // wait for everyone to get aboard
            $('.modal-title').text('Edit Person');

            $('#photo-preview').show(); // lift off

            if (data.photo) { //photo actions, self-explanatory
              $('#label-photo').text('Change Photo');
              $('#photo-preview div').
                  html('<img src="' + base_url + 'upload/' + data.photo +
                      '" class="img-responsive">');
              $('#photo-preview div').
                  append('<input type="checkbox" name="remove_photo" value="' +
                      data.photo + '"/> Remove photo when saving');
            }
            else {
              $('#label-photo').text('Upload Photo'); // label photo upload
              $('#photo-preview div').text('(No photo)');
            }

          },
          error: function() {
            debugger; // primitive error output, substantial for this project
            console.log('ERR ->editPerson');
          },
        }
    );
  };
  /*
   *Save func - does `create` or `update` in the db, depending on the crudMethod
   *  defined in the addPerson and editPerson.
   */

  this.save = function() {

    var url, formData;
    $('#btnSave').text('saving...');
    $('#btnSave').attr('disabled', true);

    if (crudMethod === 'add') {
      url = baseUrl + 'person/ajax_add';
    }
    if (crudMethod === 'update') {
      url = baseUrl + 'person/ajax_update';
    }

    formData = new FormData($('#form')[0]);
    $.ajax({
      url: url,
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      dataType: 'JSON',
      success: function(data) {

        if (data.status) //on success: close modal, reload table
        {
          $('#modal_form').modal('hide');
          reloadTable();
        }
        else {
          for (var i = 0; i < data.inputerror.length; i++) {
            $('[name="' + data.inputerror[i] + '"]').
                parent().// select parent twice to select div form-group
            parent().// class and add has-error class
            addClass('has-error');
            $('[name="' + data.inputerror[i] + '"]').
                next().// select span help-block
            text(data.error_string[i]); // to set error string
          }
        }
        $('#btnSave').text('save');
        $('#btnSave').attr('disabled', false);

      },
      error: function() {

        // debugger;
        console.log('ERR -> save');
        $('#btnSave').text('save');
        $('#btnSave').attr('disabled', false);

      },
    });
  };

  this.deletePerson = function(id) {

    if (confirm('Are you sure to delete this data?')) {
      $.ajax({
        url: baseUrl + 'person/ajax_delete/' + id,
        type: 'POST',
        dataType: 'JSON',
        success: function(data) {
          $('#modal_form').modal('hide');
          table.ajax.reload();
        },
        error: function() {
          debugger;
          console.log('ERR -> reloadTable');
        },
      });

    }
  };

  /*
   * Datatable init and build
   */
  table = $('#table').DataTable({

    'processing': true,
    'serverSide': true,
    'order': [],

    'ajax': {
      'url': baseUrl + 'person/ajax_list',
      'type': 'POST'
    },

    'columnDefs': [
      {
        'targets': [-1],
        'orderable': false
      },
      {
        'targets': [-2],
        'orderable': false
      }
    ]

  });

  // register and prep datepicker
  $('.datetimepicker').datetimepicker({
    format: 'YYYY-MM-DD'
  });

  // those annoying error masks
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

  this.reloadTable = function() {
    return table.ajax.reload();
  };

});

