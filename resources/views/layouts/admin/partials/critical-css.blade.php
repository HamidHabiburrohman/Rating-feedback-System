<style>
    /* Critical CSS - Only essential for initial render */
    *{margin:0;padding:0;box-sizing:border-box}
    body.admin-layout{background-color:#ffffff;overflow-x:hidden}
    .page-wrapper{display:flex;min-height:100vh;background-color:#ffffff}
    .left-sidebar{width:280px;background:white;border-right:1px solid #edf2f7;height:100vh;position:sticky;top:0!important;flex-shrink:0;transition:all .2s ease}
    .body-wrapper{flex:1;min-width:0;display:flex;flex-direction:column;background-color:#ffffff;min-height:100vh}
    .app-header{background-color:#ffffff;border-bottom:1px solid #e6e6e6;height:70px;display:flex;align-items:center;padding:0 30px;position:sticky;top:0;z-index:10;width:100%}
    .main-content{flex:1;padding:30px;background-color:#ffffff}
    .footer{padding:20px 30px;border-top:1px solid #edf2f7;background-color:#ffffff}
    @media (max-width:1199.98px){.left-sidebar{position:fixed;left:-280px;z-index:1050;transition:left .2s ease}.left-sidebar.show{left:0}.app-header{left:0!important;width:100%!important}}
    @media (max-width:768px){.app-header{padding:0 20px}.main-content{padding:20px}.footer{padding:20px}}
</style>