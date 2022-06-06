<?php
//[breadcrumbs]
add_shortcode('breadcrumbs', 'vd_pagebreadcrumbs');
function vd_pagebreadcrumbs($atts){
    ob_start();
  
	echo '<div class="vd-breadcrumbs">';
  		if( function_exists( 'aioseo_breadcrumbs' ) ) {
        	echo do_shortcode('[aioseo_breadcrumbs]');
  		} else {
			echo justg_breadcrumb();
  		}
	echo '</div>';
  
	return ob_get_clean();
}

add_action('wp_head','vsstemmart_ajaxurl');
function vsstemmart_ajaxurl() {
    $html    = '<script type="text/javascript">';
    $html   .= 'var ajaxurl = "' . admin_url( 'admin-ajax.php' ) . '"';
    $html   .= '</script>';
    echo $html;
}

add_action('wp_ajax_dataongkir', 'dataongkir_ajax');
function dataongkir_ajax() {
    $data       = get_option('dataongkir');
    $detail     = isset($_POST['detail']) ? $_POST['detail'] : '';
    $detail     = serialize($detail);
    if(get_option('dataongkir', null) !== null){
        update_option('dataongkir', $detail);
    } else {
        add_option('dataongkir', $detail);
    }
    wp_die();
    
}


function cek_ongkir($atts) {
	ob_start();
    $dataongkir = unserialize(get_option('dataongkir'));
	if($dataongkir){
		$datas = $dataongkir;
	} else {
		$datas = array();
	}
    $daris = array_unique(array_column($datas, 'dari'));
    $tujians = array_unique(array_column($datas, 'tujuan'));
    $ongkir = '';
    $atribut = shortcode_atts( array(
        'input'         => 'yes',
    ), $atts );
	$input = $atribut['input'];
    
    $caridari = isset($_GET['dari']) ? $_GET['dari'] : '';
    $caritujuan = isset($_GET['tujuan']) ? $_GET['tujuan'] : '';
    $cariberat = isset($_GET['berat']) ? $_GET['berat'] : '';
    foreach($datas as $data){
        if(strtolower($data['dari']) == strtolower($caridari) && strtolower($data['tujuan']) == strtolower($caritujuan)){
            $ongkir = $data['ongkir']*$_GET['berat'];
            break;
        }
    }

        if(isset($_GET['dari']) && isset($_GET['tujuan'])){
        echo '<div id="hasil-ongkir" class="card text-center my-4 bg-light text-dark">';
            echo '<div class="card-body">';
                if(empty($ongkir)){
                    echo 'Data Tidak Ditemukan';
                } else {
                    echo '<div>Ongkos Kirim dari <b>'.$caridari.'</b> ke <b>'.$caritujuan.'</b> Berat <b>'.$cariberat.'KG</b></div>';
                    echo '<div class="h3 pt-3">Rp '.number_format( $ongkir ,0 , ',','.' ).'</div>';
                }
            echo '</div>';
        echo '</div>';
        }
    ?>
    
    <div class="card my-5">
        <form class="p-4" action="#hasil-ongkir">
            <div class="row">
                <div class="col-md-4 mb-2 mb-md-0">
                    <select class="form-control" name="dari" required>
					<option value="">Pilih Asal Pengiriman</option>
                    <?php
                        foreach($daris as $dari){
                            $selected = ($dari == $caridari)? 'selected':'';
                            echo '<option value="'.$dari.'" '.$selected.'>'.$dari.'</option>';
                        }
                    ?>
                    </select>
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <select class="form-control" name="tujuan" required>
					<option value="">Pilih Tujuan Pengiriman</option>
                    <?php
                        foreach($tujians as $tujuan){
                            $selected = ($tujuan == $caritujuan)? 'selected':'';
                            echo '<option value="'.$tujuan.'" '.$selected.'>'.$tujuan.'</option>';
                        }
                    ?>
                    </select>
                </div>
                
                <div class="col-md-3 mb-2 mb-md-0"><div class="row m-0 align-items-center"><div class="col-10 p-0"><input min="1" type="number" class="form-control" name="berat" value="<?php echo ($cariberat)? $cariberat:'1'; ?>" required></div> <div class="col-2 p-1">Kg</div></div></div>
                <div class="col-md-2 text-center"><button class="btn btn-danger" type="submit">Cek Tarif <i class="ml-2 fa fa-search" aria-hidden="true"></i></button></div>
            </div>
        </form>
    </div>

    <?php if(is_user_logged_in() && ($input == 'yes')){ ?>
        <div class="card border-0">
          <div class="card-body">
            <div id="table" class="table-editable">
                <div class="row sticky-top pt-5 bg-white">
                    <div class="col-6">
                        <h5 class="text-dark">Database Ongkos Kirim</h5>
                    </div>
                    <div class="col-6">
                        <span class="float-right mb-3 mr-2">
                          <a id="export-btn" class="simpan btn btn-success btn-sm text-dark">Simpan <i class="fa fa-floppy-o" aria-hidden="true"></i></a>
                        </span>
                    </div>
                </div>
        
              <table class="table table-bordered table-responsive-md table-striped text-center">
                <thead>
                  <tr>
                    <th class="text-center text-dark" data-id="dari" >Dari Kota</th>
                    <th class="text-center text-dark" data-id="tujuan">Kota Tujuan</th>
                    <th class="text-center text-dark" data-id="ongkir">Ongkir Per KG</th>
                    <th class="text-center text-dark" data-id="">Remove</th>
                  </tr>
                </thead>
                <tbody>
                    
                <?php
                foreach($datas as $data){
                  echo '<tr>';
                    echo '<td class="pt-3-half text-dark" contenteditable="true">'.$data['dari'].'</td>';
                    echo '<td class="pt-3-half text-dark" contenteditable="true">'.$data['tujuan'].'</td>';
                    echo '<td class="pt-3-half text-dark" contenteditable="true">'.$data['ongkir'].'</td>';
                    echo '<td>';
                      echo '<span class="table-remove"><button type="button" class="btn btn-danger btn-rounded btn-sm my-0"><i class="fa fa-trash" aria-hidden="true"></i> Hapus</button></span>';
                    echo '</td>';
                  echo '</tr>';
                }
                ?>
                </tbody>
              </table>
              <div class="table-add text-center mb-3 mr-2">
                  <a href="#!" class="btn btn-danger btn-sm">Tambah Data <i class="fas fa-plus" aria-hidden="true"></i></a>
              </div>
            </div>
          </div>
        </div>
    <?php } 
	return ob_get_clean();
}
add_shortcode('cek-ongkir','cek_ongkir');

add_action( 'init', 'cekresi' );
function cekresi() {
	$labels = array(
		'name'               => _x( 'Cek Resi', 'post type general name', 'vsstem' ),
		'add_new'               => _x( 'Tambah Resi', 'post type general name', 'vsstem' ),
	);

	$args = array(
		'labels'             => $labels,
        'description'        => __( 'Description.', 'vsstem' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'cekresi' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title')
	);

	register_post_type( 'cekresi', $args );
}


add_filter( 'rwmb_meta_boxes', 'vsstem_register_meta_boxes' );
function vsstem_register_meta_boxes( $meta_boxes ) {
    $meta_boxes[] = array(
        'id'                => 'noresi',
        'title'             => 'Detail Resi',
        'post_types'        => 'cekresi',
        'context'           => 'normal',
        'priority'          => 'high',

        'fields' => array(
            array(
				'type'      => 'heading',
				'name'      => esc_html__( 'Status Pengiriman', 'vsstem' ),
				'desc'      => esc_html__( '', 'vsstem' ),
			),
			
			array(
                'name'      => 'Status',
                'desc'      => 'contoh: <b>12 Juni 2020 = Sedang dalam proses pengepakan</b>',
                'id'        => 'opsiresi',
                'type'      => 'text',
                'clone'     => 'true',
            ),
        )
    );

    return $meta_boxes;
}

function loop_filter_cekresi(){
    $a = isset($_GET['a'])? $_GET['a'] : '';
    $posttype = 'cekresi';
    $my_query = new WP_Query( 's='.$a.'&post_type='.$posttype );
    $html = '<div class="loopfilter">';
    if ( $my_query->have_posts() ) { 
    	while ( $my_query->have_posts() ) { 
    		$my_query->the_post();
    		$html .= '<div class="row">';
    		    $html .= '<div class="judul-resi col-md-4">';
                // $html .='<a href="'.get_the_permalink().'">'.get_the_title().'</a>' .'<br>';
    		    $html .= '</div>';
    		$html .= '</div>';
    	}
    } else {
        $html .= 'pencarian untuk no resi '. $a .' tidak ditemukan';
    }
    wp_reset_postdata();
    $html .= '</div>';
    return $html;
}
add_shortcode('loopcekresi', 'loop_filter_cekresi' );

add_action('wp_ajax_nopriv_cekresi', 'cekresi_ajax');
add_action('wp_ajax_cekresi', 'cekresi_ajax');
function cekresi_ajax() {
    $nama = isset ($_POST ['nama'])?$_POST ['nama']:'';
    $page = get_page_by_title($nama, OBJECT, 'cekresi');

 
    echo '<table class="table table-striped table-bordered">';
        echo '<thead class="thead-dark"><tr>';
        echo ' <th scope="colspan="4"">No Resi</th>';
        echo ' <th scope="colspan="8"">Status</th>';
        echo '</thead></tr>';
                $dataresi = rwmb_meta('opsiresi','', $page->ID);
                if ($dataresi){
               $i = 0;
                foreach  ( $dataresi as $data ) {
                $nomor = ++$i;                
                   echo ' <tr>';
                      echo ' <td class="no-resi">';
                      if ($nomor==1){
                           echo get_the_title ($page->ID);
                      }
               
        echo '</td>';
                echo ' <td class="status-resi">';
                        echo  $data.'<br>';
                   echo ' </td>';
                   echo '</tr>';
                }
            }else {
            echo 'Data resi tidak ditemukan';
        }
    echo '</table>';
        echo  '</div>';
        
    exit();
}



function cekresipencarian() {
    ob_start(); ?>
    <div class="pencarian-resi card shadow p-3">
    <div class="pencarian text-right" action="?" method="get">
        <input id="noresi" type="text" name="a" class="form-control w-100" value="" placeholder="Nomor Resi" />
        <div class="w-100 text-left mt-2 mb-3 text-dark"><small><i>Klik Tombol Lacak Untuk Melihat Detail</i></small></div>
        <button class="berhasil btn btn-danger px-4"><b>Lacak <i class="ml-2 fa fa-truck"></i></b></button>
    </div>
    </div>
    <div class="hasil pt-4">

    </div>
    <script>
        jQuery(function($) {
            $('.berhasil').click(function(){
                var noresi = $('#noresi').val();
                // alert (noresi);
                if (noresi){
                $('.hasil').html('<i class="fa fa-spinner fa-spin"></i> loading...');
                jQuery.ajax({
                    type    : "POST",
                    url     : ajaxurl,
                    data    : {action:'cekresi', nama:noresi },  
                    success :function(data) {
                        $('.hasil').html(data);
                    },
                });
                } else {
                    alert('Masukkan nomor resi.');
                }
            })
        });
    </script>
    <?php return ob_get_clean();
}
add_shortcode('cek-resi', 'cekresipencarian');