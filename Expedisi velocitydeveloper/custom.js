jQuery(function($) {
    const $tableID = $('#table');
         const $BTN = $('#export-btn');
         const $EXPORT = $('#export');
        
         const newTr = `
         <tr class="hide">
          <td class="pt-3-half text-dark" contenteditable="true">-</td>
          <td class="pt-3-half text-dark" contenteditable="true">-</td>
          <td class="pt-3-half text-dark" contenteditable="true">-</td>
          <td>
            <span class="table-remove"><button type="button" class="btn btn-danger btn-rounded btn-sm my-0 waves-effect waves-light"><i class="fa fa-trash" aria-hidden="true"></i> Hapus</button></span>
          </td>
        </tr>`;
        
         $('.table-add').on('click', '.btn', () => {
           $tableID.find('table').append(newTr);
         });
        
         $tableID.on('click', '.table-remove', function () {
           $(this).parents('tr').detach();
         });
        
         // A few jQuery helpers for exporting only
         jQuery.fn.pop = [].pop;
         jQuery.fn.shift = [].shift;
        
         $BTN.on('click', () => {
        
           const $rows = $tableID.find('tr:not(:hidden)');
           const headers = [];
           const data = [];
            
            $('.simpan').html('Prosses <i class="fa fa-spinner fa-spin fa-fw"></i>');
           // Get the headers (add special header logic here)
           $($rows.shift()).find('th:not(:empty)').each(function () {
             headers.push($(this).attr('data-id').toLowerCase());
           });
        
           // Turn all existing rows into a loopable array
           $rows.each(function () {
             const $td = $(this).find('td');
             const h = {};
        
             // Use the headers from earlier to name our hash keys
             headers.forEach((header, i) => {
               h[header] = $td.eq(i).text();
             });
             data.push(h);
           });
            jQuery.ajax({
                type    : "POST",
                url     : ajaxurl,
                data    : {action:'dataongkir', detail:data },  
                success :function(data) {
                    $('.simpan').html('Berhasil <i class="fa fa-check" aria-hidden="true"></i>');
                    setTimeout(function() {
                        $('.simpan').html('Simpan <i class="fa fa-floppy-o" aria-hidden="true"></i>');
                    }, 1000);
                    
                },
            });
    
         });
    });