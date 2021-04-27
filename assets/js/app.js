// assets/js/app.js
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
//import '../css/app.css';
import '../css/app.scss';
import Swal from 'sweetalert2/dist/sweetalert2.js'

import 'sweetalert2/src/sweetalert2.scss'

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';
const axios = require('axios').default;
require('bootstrap');


(function() {
    
    $('.todo-delete').on( 'click', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const id = $(this).data('id');
                axios.delete( $(this).prop( 'href' ))
                  .then(function (response) {

                    $('#todo-' + id ).remove();

                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    );

                  })
                  .catch(function (error) {
                    console.log(error);
                  });
                
                
            }
        })

    });
 
 })();

/**/