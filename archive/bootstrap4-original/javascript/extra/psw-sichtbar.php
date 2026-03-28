<?php
//BOC "show pass" implementation, 05-2021, noRiddle
?>
<script>
$(function() {
    if($('input[type="password"]').length) {
        let $ipw = $('input[type="password"]'),
            sh_pw = ' <i class="shw-pw fa fa-eye"></i>';
        $ipw.each(function() {
            let $this = $(this),
                th = $this.outerHeight(),
                icfs = 22,
                nth = th/2 - icfs/2;
            $this.wrap('<div class="pssw-wrap" style="position:relative;"></div>');
            $(sh_pw).insertAfter($this).css({'position':'absolute', 'top':nth+'px', 'right':'5px', 'font-size':icfs+'px', 'color':'#555', 'cursor':'pointer'});
        });
        $('.shw-pw').on('click', function() {
            let $that = $(this);
                $tpi = $that.prev('input'),
                tpiattp = $tpi.attr('type'),
                tswp = tpiattp == 'password' ? true : false;

             $tpi.prop('type', (tswp ? 'text' : 'password'));
             $that.toggleClass('fa-eye', !tswp).toggleClass('fa-eye-slash', tswp);
        });
    }
});
</script>
<?php
//EOC "show pass" implementation, 05-2021, noRiddle
?>