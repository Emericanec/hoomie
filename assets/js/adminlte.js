import jQuery from 'jquery';
import '../css/fonts.scss'
import '../../vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css'
import '../../vendor/almasaeed2010/adminlte/plugins/fontawesome-free/css/all.css'
import '../../vendor/almasaeed2010/adminlte/dist/js/adminlte.min'

$(document).on('click', '#sidebar-overlay', () => { $('.nav-link').click();});