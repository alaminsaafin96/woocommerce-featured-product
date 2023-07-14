jQuery(document).ready(function(){
  jQuery("#wcfp_enable_ed").on("click",function() {
    if(jQuery(this).is(":checked"))
    {
        jQuery("#wcfp_enable_ed").val('yes');
    }
    else
    {
        jQuery("#wcfp_enable_ed").val('no');
    }
    jQuery("#wpcf_expiration_date_fields").toggle();
  });


  jQuery("#wcfp_enabled").on("click",function() {
    if(jQuery(this).is(":checked"))
    {
        jQuery("#wcfp_enabled").val('yes');
    }
    else
    {
        jQuery("#wcfp_enabled").val('no');
    }
  });

  document.getElementById("wcfp_expire_date").addEventListener("input", function (evt){

    const expire_date = this.value;
    const expire_time = jQuery("#wcfp_expire_time").val();

    var date = new Date();

    var getYear = date.toLocaleString("default", { year: "numeric" });
    var getMonth = date.toLocaleString("default", { month: "2-digit" });
    var getDay = date.toLocaleString("default", { day: "2-digit" });

    var today_date = getYear + "-" + getMonth + "-" + getDay;

    var today_time = date.getHours() + ":" + date.getMinutes();
    if(expire_date < today_date){

        jQuery("#wcfp_enabled").val("no");
        jQuery("#wcfp_enabled").prop('checked', false);
        jQuery("#wcfp_enabled").prop('disabled', true); 
        alert("you can't select the product as promoted because expired date should be greater than today date !");
    
    }else if(expire_date == today_date){

        if(expire_time < today_time)
        {
            alert("please select time greater than current time!");
            jQuery("#wcfp_enabled").val("no");
            jQuery("#wcfp_enabled").prop('checked', false);
            jQuery("#wcfp_enabled").prop('disabled', true); 
        }else{
            jQuery("#wcfp_enabled").prop('disabled', false);
        }
    }
    else{
        jQuery("#wcfp_enabled").prop('disabled', false);
    }

  });

  document.getElementById("wcfp_expire_time").addEventListener("input", function (evt){

        var date = new Date();

        var getYear  = date.toLocaleString("default", { year: "numeric" });
        var getMonth = date.toLocaleString("default", { month: "2-digit" });
        var getDay   = date.toLocaleString("default", { day: "2-digit" });

        var   today_date   = getYear + "-" + getMonth + "-" + getDay;
        const expire_date  = jQuery("#wcfp_expire_date").val();

        const expire_time = this.value;
        var   date        = new Date();
        var   today_time  = date.getHours() + ":" + date.getMinutes();

        if(today_date == expire_date)
        {
            if(expire_time < today_time)
            {
                alert("please select time greater than current time!");
            }else{
                jQuery("#wcfp_enabled").prop('disabled', false);
            }
        }
        

  });

});


