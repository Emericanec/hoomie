import '../css/fonts.scss'
import '../../vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css'
import '../../vendor/almasaeed2010/adminlte/plugins/fontawesome-free/css/all.css'
import 'icheck-bootstrap'
import '../../vendor/almasaeed2010/adminlte/dist/js/adminlte.min.js'
//import '../../vendor/almasaeed2010/adminlte/plugins/bootstrap/js/bootstrap.min.js'
import '@fortawesome/fontawesome-free/css/all.css'
//import '@fortawesome/fontawesome-free/js/all'
import '../css/adminlte.css'
import './vue'

$(document).on('click', '#sidebar-overlay', () => { $('.nav-link').click();});