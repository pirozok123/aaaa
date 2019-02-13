  <?php

  require("functions/registration.php");

  $countries = getCountries();

  include('blocks/head.php'); 

  if(isset($_GET["logout"]))
    unset($_SESSION["user_id"]);

  if(!isset($_SESSION["user_id"]))
    header("Location:/login/");
 
   ?>

  <body>
  <?php include('blocks/header.php'); ?>  

    <!--breadcrumbs start-->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-sm-4">
                    <h1>Profile</h1>
                </div>
                <div class="col-lg-8 col-sm-8">
                    <ol class="breadcrumb pull-right">
                        <li><a href="#">Home</a></li>
                        <li class="active">Profile</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!--breadcrumbs end-->

      <!--information block-->
    <ul class="progress-indicator" style="margin-top:10px">
            <li class="completed">
                <span class="bubble"></span>
                <b>Personal info ( 100% completed  )</b>
            </li>
            <li>
                <span class="bubble"></span>
                <b>Add works ( 0% completed  )</b>
            </li>
            <li>
                <span class="bubble"></span>
                <b>Add docs ( 0% completed  )</b>
            </li>
            <li>
                <span class="bubble"></span>
                <b>Bank info ( 0% completed  )</b>
            </li>
            <li>
                <span class="bubble"></span>
                <b>Close relatives ( 0% completed  )</b>
            </li>
        </ul>
    <!--information block-->

<?php

include_once("registration/db.php");  
$usersdata = getUserData();

//echo "<pre>";
//print_r($usersdata);
//echo "</pre>";

$diena = ((int)$usersdata["diena"] < 10) ? "0".$usersdata["diena"] : (int)$usersdata["diena"];

$years = array();
$issilavyears = array();
$atliktiyears = array();
$pasport_expiry_years = array();
$pasport_issue_years = array();

$starting_year = $issilav_starting_year = $atlikti_starting_years = $pasport_expiry_start = $pasport_issue_end = date('Y');
$ending_year = $education_ending_year  = $finished_ending_year = 1950;
$pasport_expiry_end = $pasport_expiry_start + 10;
$pasport_issue_start = $pasport_issue_end - 20;

$menesiai = array('01','02','03','04','05','06','07','08','09','10','11','12');

for($starting_year; $starting_year <= $ending_year; $starting_year++) {
$years[] = '<option value="'.$starting_year.'" '.(!empty($usersdata["metai"]) && $usersdata["metai"] == $starting_year ? 'selected' : '').'>'.$starting_year.'</option>';
}

for($pasport_expiry_end; $pasport_expiry_end >= $pasport_expiry_start; $pasport_expiry_end--) {
$pasport_expiry_years[] = '<option value="'.$pasport_expiry_end.'">'.$pasport_expiry_end.'</option>';
}


for($pasport_issue_end; $pasport_issue_end >= $pasport_issue_start; $pasport_issue_end--) {
$pasport_issue_years[] = '<option value="'.$pasport_issue_end.'">'.$pasport_issue_end.'</option>';
}

for($issilav_starting_year; $issilav_starting_year >= $education_ending_year; $issilav_starting_year--) {
$issilavyears[] = '<option value="'.$issilav_starting_year.'">'.$issilav_starting_year.'</option>';
}

for($atlikti_starting_years; $atlikti_starting_years <= $finished_ending_year; $atlikti_starting_years++) {
$atliktiyears[] = '<option value="'.$atlikti_starting_years.'">'.$atlikti_starting_years.'</option>';
}

?> 

 <script>
     function removeIssilavinimoBlocks(button, blockname){

     var parentblock = jQuery(button).parents('.'+blockname);

     jQuery(parentblock).not(jQuery('.'+blockname).first()).remove();
     jQuery(parentblock).find('div.addtran').css('display','inline-block');
     jQuery(parentblock).find('textarea').val('');
     jQuery(parentblock).find('input[type=file]').val('');
     jQuery(parentblock).css('display','none');
   
     var issblockslit  = blockname.split('-')[0];
     jQuery("."+issblockslit+"-add").css('display','block');

    }

    function addremovebutton(blockname){

       var removeName;

       if(blockname.indexOf('issilavinimas') > -1) removeName = ' educations';
       else if(blockname.indexOf('atlikti') > -1) removeName = ' work experiences';
       else if(blockname.indexOf('other') > -1) removeName = ' other works';

       //jQuery('.'+blockname).find('button.removeissilavbutton').remove();
       //jQuery("<button type='button' class='removeissilavbutton' onclick='removeIssilavinimoBlocks(\""+blockname+"\");return false;'>Remove all "+removeName+"</button>").appendTo(jQuery('.'+blockname).last());

    }
    
    function copyTranLaikas(copy,blockname){
       var tranlaikas = jQuery(copy).parents('.'+blockname+'');
       var tranlaikasclass = jQuery(tranlaikas).attr('class').split(' ')[2];
       jQuery(tranlaikas).removeClass(tranlaikasclass);

       var parentDiv = jQuery(tranlaikas).clone();

       if(jQuery('.'+blockname).length == 20) { alert('You can not add more than 20 works !'); return;}

       var tranlaikaslength = jQuery('.'+blockname+' div.addtran').length;

       jQuery(parentDiv).insertAfter(jQuery(copy).parents('.'+blockname+':last')); 

       jQuery('.'+blockname+' div.addtran').each(function(index){

         if(index != tranlaikaslength)  jQuery(this).hide();
         if(index >0) jQuery('.'+blockname+' div.removetran:eq('+index+')').show().css('display','inline-block');
      
      });    

      //addremovebutton(blockname); 

     }
      
     function removeTranLaikas(remove, blockname){
       jQuery(remove).parents('.'+blockname+'').remove();

       jQuery('.'+blockname+' div.addtran:last').show().css('display', 'inline-block');

       //addremovebutton(blockname);

     }


     function unsetAllWorks(work){
      var works = document.querySelectorAll('input[name='+work+']');
      var works_experience = document.querySelectorAll('#'+work.replace("_", "-")+' select');
       for(var prof = 0; prof<works.length; prof++){
          works[prof].checked = false;
          works_experience[prof].selectedIndex = 0;
       }
     }

     function showhideprof(){

      document.getElementById('outside-works').style.display = 'none';
      document.getElementById('inside-works').style.display = 'none';
      document.getElementById('divkitaprof').style.display = 'none';

      var profesijachecked = document.querySelectorAll('input[name=profesija]');

 
      for(var profesija = 0; profesija<profesijachecked.length; profesija++){

          if(profesijachecked[profesija].value == 3 && profesijachecked[profesija].checked ){
            document.getElementById('outside-works').style.display = 'block';
          }
          else if(profesijachecked[profesija].value == 3 && !profesijachecked[profesija].checked)  {
            unsetAllWorks('outside_works');
          }

          if(profesijachecked[profesija].value == 4 && profesijachecked[profesija].checked){
            document.getElementById('inside-works').style.display = 'block';
          }
          else if(profesijachecked[profesija].value == 4 && !profesijachecked[profesija].checked) {
            unsetAllWorks('inside_works');
          }
  
          if(profesijachecked[profesija].value == 5 && profesijachecked[profesija].checked){
            document.getElementById('divkitaprof').style.display = 'block';
          }
          else if(profesijachecked[profesija].value == 5 && !profesijachecked[profesija].checked){
            unsetAllWorks('divkitaprof');
          }

     }
   }

  </script>

  <script>
    function updateUser(){
 
     var $update = $("#about"); 
   
     var $outside_works = $update.find('.one-outside:not([style*="display: none"])');
     var $inside_works = $update.find('.one-inside:not([style*="display: none"])');
 
     var about = {
        role:$update.find('input[name=role]:checked').val(),
        first_name:$update.find('input[name=first_name]').val(),
        last_name:$update.find('input[name=last_name]').val(),
        year:$update.find('select[name=year]').val(),
        month:$update.find('select[name=month]').val(),
        day:$update.find('select[name=day]').val(),
        phone:$update.find('input[name=phone]').val(),
        country:$update.find('select[name=country]').val(),
        city:$update.find('input[name=city]').val(),
        adresas:$update.find('input[name=adresas]').val(),
        pasto_kodas:$update.find('input[name=pasto_kodas]').val(),
        short:$update.find('textarea[name=short]').val(),
        img_name:photo_data.img_name
     }

     if($outside_works.length != 0){

     about["outside"] = {};

     $.each($outside_works, function(index){

     var outside_vals = $(this).find("select[disabled] option:selected").val(); 

     if(typeof outside_vals !== 'undefined')	

     about.outside[index] = outside_vals;
    
       });

      }

     if($inside_works.length != 0){

     about["inside"] = {};

     $.each($inside_works, function(index){

     var inside_vals = $(this).find("select[disabled] option:selected").val(); 

     if(typeof inside_vals !== 'undefined')	

     about.inside[index] = inside_vals;
    
       });

      }

   
     $.ajax({
        url: '/template/functions/profile.php',
        dataType: 'json',
        type: 'post',
        //contentType: "application/json; charset=utf-8",
        data:{ajax: 1, userdata: about, action: 'personal'},
         beforeSend: function(data){
             $update.replaceWith('<img src=\'/template/img/loaders/giphy.gif\' class=\'photo-loader\'>');
         },
        success:function(data){	
      
         $(".photo-loader").replaceWith($update); 

         var message = 'Your profile successfully updated';

         openMagnifyMessage(message);
  
        },
        error:function(a, b, c){
          //$("#myModal p").text('Some errors occurred while updating your profile. Please try again later!');
          //$("#modal-button").click();
          //alert('Some errors occurred while updating your profile. Please try again later!');
         //console.log(a);
         //console.log(b);
         //console.log(c);
        }
      });
     }

     function updateBank(relative = false){ 

      var $update = (relative === false ? $("#bank") : $("#close-relative-block"));

      var i = 0;

      var blocklength = (relative === false ? $("div.bank").length : $("div.close-relative").length);

      var bank = {};

      if(relative === false){

      $.each($("div.bank"), function(){

      bank["city_of_bank_"+i+""] = $(this).find('input[name=city_of_bank]').val();  
      bank["bankname_"+i+""] = $(this).find('input[name=bankname]').val();  
      bank["account_number_"+i+""] = $(this).find('input[name=account_number]').val();     
      i++;

      }); 

      }

      else {
      $.each($("div.close-relative"), function(){

      bank["relative_name_"+i+""] = $(this).find('input[name=relative_name]').val();  
      bank["relative_surname_"+i+""] = $(this).find('input[name=relative_surname]').val();  
      bank["relative_phone_"+i+""] = $(this).find('input[name=relative_phone]').val();     
      i++;

      });   
      }

      $.extend(bank, {blockcount: blocklength});

      //console.log(bank);
      //return false;

      $.ajax({
            url: '/template/functions/profile.php',
            data:{userdata: bank, action: (relative === false ? 'bank' : 'relative')},
            type: 'POST',
            dataType : 'json',
            beforeSend: function(data){

             $update.replaceWith('<img src=\'/template/img/loaders/giphy.gif\' class=\'photo-loader\'>');

            },

            success : function(data){
             
            console.log(data);

            //console.log(data);

          //alert('Changes successfullyy saved. You can continue working with your profile!');
            },
           error:function(a, b, c){
            //console.log(a);
            //console.log(b);
            //console.log(c);
          //$("#myModal p").text('Some errors occurred while updating your profile. Please try again later!');
          //$("#modal-button").click();

            },

            complete : function(b){

            var message = (b && b == 'error' ? 'Some errors occurred while updating your profile. Please try again later!' : 'Your '+(relative === false ? 'bank' : 'close relatives')+' information successfully changed!');
              
            $("body").find('.photo-loader').replaceWith($update);
            $("#myModal p").text(message);
            $("#modal-button").click();
            },

        });
     }


      function updateWorks(){
      
      var work = false;

      $("#outside-works select, #inside-works select").each(function(){
       var workvalue = +$(this).val();
       if(workvalue !=0) { return work = true;}
      });

      if(work === false) alert('You can not add any work, because you have no selected experences of your work!');

     }

      function updateFinWork(work){
 
     var $workscontent = $(work); 
     var $works_result = $workscontent.find('.input-group');
     var $textarea = $workscontent.find('textarea');
     var $select = $workscontent.find('select');
 
     var worksdata = {
        description:$textarea.val(),
        year:$select.val(),
        work_id:$workscontent.find('input[name=work_id]').val()
     }

     $.ajax({
        url: '<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/upload4jquery.php',
        dataType: 'json',
        type: 'post',
        //contentType: "application/json; charset=utf-8",
        data:{ajax: 1, userdata: worksdata, action: 'update_fin_work'},
         beforeSend: function(){
             $works_result.replaceWith('<img src=\'/template/img/loaders/giphy.gif\' class=\'photo-loader\'>');
         },
        success:function(data){ 

         $textarea.attr('readonly', 'true');
         $select.attr('disabled','disabled'); 

          openMagnifyMessage('Work successfully updated!');

         // console.log(data);

         //$myModalcontent = $("#myModal").find('.modal-content');  
      
         $(".photo-loader").replaceWith($works_result); 
       
          //$("<p class='profile-message'>Your profile successfully updated!</p>").appendTo($myModalcontent);

           //document.getElementById('myBtn').click();

          // openCloseMyModal();
  
        },
        error:function(a, b, c){
          //$("#myModal p").text('Some errors occurred while updating your profile. Please try again later!');
          //$("#modal-button").click();
          //alert('Some errors occurred while updating your profile. Please try again later!');
         console.log(a);
         console.log(b);
         console.log(c);

        }
      });
     }



  </script>

    <!--container start-->
    <div class="component-bg">
      <div class="container">

             <!-- Modal -->
        <!--<div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Message</h4>
        </div>
        <div class="modal-body">
        <p>Your profile successfully updated!</p>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Continue work</button>
        </div>
        </div>
        </div>
        </div>-->

        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" style="display:none;" id="modal-button">Open Modal</button>

        <div class="bs-docs-section">
          <h1 id="tables" class="page-header h1profile" onclick="showblock('about'); return false;" style="background-color: #EFF5FB; padding: 2px;">
            Personal information:
          </h1>
          <div class="bs-example" id="about" style="display:none;background-color: #EFF5FB; padding: 2px;">
          <table class="table">
         <tbody>   
          <tr>
          <td align="right">Personal photo: </td> 

          <?php $avatar = empty($usersdata["user_img"]) && @file_exists(ROOT."/template/uploads/a_photo/".$usersdata["id"]."/".$usersdata["user_img"]); ?>

          <td align="left"><div><button style="color:black;width: 132px;margin-bottom:2px;" type="button" class="btn btn-warning" onclick="window.personal_photo.click(); return false;">Upload new photo</button></div><div class="personal-photo"><img id="avatar_img" src="<?=($avatar === false ? "/template/uploads/a_photo/".$usersdata["id"]."/".$usersdata["user_img"] : "/template/img/avatars/upload_photo_png170x170.png"); ?>" alt="Upload photo" width="132" height="170" title="Upload photo" <?=($avatar===true ? 'onclick="window.personal_photo.click();"' : '')?> onclick="openMagnify(this.src, 'perss_photo');"></div>
          <input type="file" id="personal_photo" onchange="setPersonalPhoto(this, 'personal');" style="display:none;">
          </td> 
          </tr>
           <tr>
            <td style="border-top:none;" align="right">Short description:</td> 
            <td align="right" style="border-top:none;"><textarea class="form-control" name="short" placeholder="Short description:"><?=$usersdata["short_description"]?></textarea></td> 
          </tr>
              <tr>
                <td align="right">Gender:
                </td> 
                   <td>
                  <div class="radios">
                        <label class="label_radio col-lg-6 col-sm-6" for="radio">
                            <input name="role" id="radio-01" value="1" type="radio" <?=$usersdata["role"] == 1 ? 'checked=\'checked\''  : '' ?> name="role" onchange="setRadioButton(this);"> Male
                        </label>
                        <label class="label_radio col-lg-6 col-sm-6" for="radio">
                            <input name="role" id="radio-02" value="2" type="radio" name="role" <?=$usersdata["role"] == 2 ? 'checked=\'checked\''  : '' ?>onchange="setRadioButton(this);"> Female
                        </label>
                 </div></td> 
              </tr>
              <tr>
                <td align="right">First Name:
                </td> 
                   <td><input type="hidden" class="form-control" placeholder="First Name" autofocus="" name="first_name" value="<?=$usersdata["first_name"]?>"><?=$usersdata["first_name"]?> <span class="glyphicon glyphicon-edit" onclick="this.previousElementSibling.type = 'text'; this.parentElement.innerHTML = this.previousElementSibling.outerHTML; return false; "></span></td> 
              </tr>
                <tr>
                <td align="right">Last Name:
                </td> 
                   <td><input type="hidden" class="form-control" placeholder="Last Name" autofocus="" name="last_name" value="<?=$usersdata["last_name"]?>"><?=$usersdata["last_name"]?> <span class="glyphicon glyphicon-edit" onclick="this.previousElementSibling.type = 'text'; this.parentElement.innerHTML = this.previousElementSibling.outerHTML; return false; "></span>
                </td> 
              </tr>
                 <tr>
                <td align="right">Email Address (Can not be changed, because is your username):
                </td> 
                   <td><?=$usersdata["email"]?>
                   <a href="#" onclick="alert('Under construction. Check this later!'); return false;">Verify</a>
                </td> 
              </tr>
                <tr>
                <td align="right">Birthday:
                </td> 
                   <td><select class="form-control" onchange="getDays('year', this);" id="year" name="year">
               <option value="Sausis" <?php if(empty($usersdata["metai"])) echo 'selected'; ?> disabled>Select Year</option>
           <?php echo implode("\n\r", $years);  ?>
                  </select>
                     <select class="form-control" <?=(empty($usersdata["metai"]) ? 'selected disabled style="opacity:0.3"'  : '')?> id="month" name="month" >
              <option value="1" <?php if(empty($usersdata["menuo"])) echo 'selected'; ?> disabled>Select Month</option>
          <?php foreach($menesiai as $key => $menuo) : ?>
            <option value="<?=$menuo?>" <?=!empty($usersdata["menuo"]) && $menuo == $usersdata["menuo"] ?  'selected': '' ?>><?=$menuo?></option>
          <?php endforeach; ?>
                  </select>
                     <select class="form-control" <?=(empty($usersdata["metai"]) ? 'selected disabled style="opacity:0.3"'  : '')?> id="day" name="day">
               <option <?=empty($usersdata["diena"]) ?  'selected': '' ?> disabled>Select Day</option>
               <?=(!empty($usersdata["diena"]) ? '<option value="'.$diena.'" selected>'.$diena.'</option>' : '') ?>
                    </select>
                 </td> 
              </tr>
                <tr>
                <td align="right">Phone:
                </td> 
                <td id="teltd"><input type="hidden" placeholder="" autofocus="" name="phone" id="phone" class="phonesflags" value="<?=$usersdata["telefonas"]?>"><?=$usersdata["telefonas"]?> <span class="glyphicon glyphicon-edit" onclick="this.previousElementSibling.type = 'tel'; 
                  
                  var phonehtml = this.previousElementSibling.outerHTML;      
                  phonehtml += window.verifytel.innerHTML;  
                  window.teltd.innerHTML = phonehtml; setPhone(window.teltd); return false; "></span>
               </td> 
                <td style="display:none;" id="verifytel"><a href="#" onclick="alert('Under construction. Check this later!'); return false;">Verify</a></td>
              </tr>
                <tr>
                <td align="right">Country:
                </td> 
                   <td>
              <select class="form-control" name="country">
               <option disabled>Select Country</option>
          <?php foreach($countries as $country) : ?>
            <option value="<?=$country?>" <?=!empty($usersdata["salis"]) && $usersdata == $usersdata["salis"] ?  'selected': ($country == 'Lithuania' ? 'selected' : '') ?>><?=$country?></option>
          <?php endforeach; ?>
                  </select>
                </td> 
              </tr>
            <tr>
                <td align="right">Experience (Outside works)<br/>Max 5 works:
                </td> 
                  
          <?php 
          
          $outside_list = getWorks(); 

          ?>
          <script>
           
          var AllOutSideWorks = '<?=json_encode($outside_list);?>';  
          AllOutSideWorks = JSON.parse(AllOutSideWorks);

          </script>

          <?php

          $outside = getWorks(false, true);

          if(count($outside) == 0):

          ?> 

          <td>


        <a href="#" onclick="this.nextElementSibling.style.display = 'inline-block'; this.style.display = 'none'; resetOutsideWorks('outside'); return false;">
          <span class="glyphicon glyphicon-plus"></span>
          </a> 		

          <div class="one-outside" style="display: none;">
	      <select class="form-control" name="outside" style="width: 140px;">
	       <option disabled>Select Work</option>

          <?php foreach($outside_list as $work) : ?>
            <option value="<?=$work["id"]?>"><?=$work["name"]?></option>
          <?php endforeach; ?>
             </select>
                <button type="button" class="btn btn-warning one-outside-button" onclick="if(confirm('Are you want add more works?')) addMoreOutside(this, 'confirmed'); else addMoreOutside(this, 'not confirmed');">Add</button>
                <button type="button" class="btn btn-danger one-outside-remove-button" onclick="if(confirm('Are you sure?')) removeOneOutside(this);">Remove</button>
          </div>      
                </td> 
          <?php else : ?>

           <script>
           
          var outside_list = true;

          </script>	 	

          <td>

        <a href="#" onclick="this.nextElementSibling.style.display = 'inline-block'; this.style.display = 'none'; resetOutsideWorks('outside'); return false;" style="display: none;">
          <span class="glyphicon glyphicon-plus"></span>
          </a> 	

        <?php 
 
        foreach($outside as $key => $work) : ?>

	     <div class="one-outside">
	      <select class="form-control" name="outside" style="width: 140px;" disabled>
	       <option disabled>Select Work</option>

            <option value="<?=$work["works_id"]?>"><?=$work["name"]?></option>
         
             </select>
                <button type="button" class="btn btn-warning one-outside-button" onclick="if(confirm('Are you want add more works?')) addMoreOutside(this, 'confirmed'); else addMoreOutside(this, 'not confirmed');" style="<?=(count($outside)==($key+1) && count($outside)!=5 ? '"opacity:5"' : 'opacity:0')?>">Add</button>
                <button type="button" class="btn btn-danger one-outside-remove-button" onclick="if(confirm('Are you sure?')) removeOneOutside(this);">Remove</button> 
          </div>       
      <?php endforeach; ?>
                </td> 
	  <?php endif; ?>
              </tr>
                <tr>
          <td align="right">Experience (Inside works)<br/>Max 5 works:
          </td>      

          <?php 

          $inside = getWorks(true, true); 

          $inside_list = getWorks(true); ?>

          <script>
           
          var AllInsideWorks = '<?=json_encode($inside_list);?>';  
          AllInsideWorks = JSON.parse(AllInsideWorks);

          </script>	
        
         <?php if(count($inside) == 0):

          ?>

          <td>

          <a href="#" onclick="this.nextElementSibling.style.display = 'inline-block'; this.style.display = 'none'; resetOutsideWorks('inside'); return false;">
          <span class="glyphicon glyphicon-plus"></span>
          </a>

          <div class="one-inside" style="display: none;"> 
	      <select class="form-control" name="inside" style="width: 140px;">
	       <option disabled>Select Work</option>

          <?php

          foreach($inside_list as $work) : ?>
            <option value="<?=$work["id"]?>"><?=$work["name"]?></option>
          <?php endforeach; ?>
                  </select>
                <button type="button" class="btn btn-warning one-inside-button" onclick="if(confirm('Are you want add more works?')) addMoreOutside(this, 'confirmed'); else addMoreOutside(this, 'not confirmed');">Add</button>
                <button type="button" class="btn btn-danger one-inside-remove-button" onclick="if(confirm('Are you sure?')) removeOneOutside(this);">Remove</button>
          </div>      
                </td> 

          <?php else : ?>

          <script>
           
          var inside_list = true;

          </script>		

          <td>
	      <a href="#" onclick="this.nextElementSibling.style.display = 'inline-block'; this.style.display = 'none'; resetOutsideWorks('inside'); return false;" style="display:none;">
          <span class="glyphicon glyphicon-plus"></span>
          </a> 

          <?php foreach($inside as $key => $work) : ?>

          <div class="one-inside"> 
	      <select class="form-control" name="inside" style="width: 140px;" disabled>
	       <option disabled>Select Work</option>
            <option value="<?=$work["id"]?>"><?=$work["name"]?></option>
                  </select>     
                <button type="button" class="btn btn-warning one-inside-button" onclick="if(confirm('Are you want add more works?')) addMoreOutside(this, 'confirmed'); else addMoreOutside(this, 'not confirmed');" style="<?=(count($inside)==($key+1) && count($inside)!=5 ? '"opacity:5"' : 'opacity:0')?>">Add</button>
                <button type="button" class="btn btn-danger one-inside-remove-button" onclick="if(confirm('Are you sure?')) removeOneOutside(this);">Remove</button>
          </div> 

          <?php endforeach; ?>
                </td> 
			<?php endif; ?>
              </tr>
                <tr>
                <td align="right">City:
                </td> 
                   <td><input type="hidden" class="form-control" placeholder="City" autofocus="" name="city" value="<?=$usersdata["miestas"]?>"><?=$usersdata["miestas"]?> <span class="glyphicon glyphicon-edit" onclick="this.previousElementSibling.type = 'text'; this.parentElement.innerHTML = this.previousElementSibling.outerHTML; return false; "></span>
                </td> 
              </tr>
              <tr>
                <td align="right">Address:
                </td> 
                   <td><input type="hidden" class="form-control" placeholder="Address" autofocus="" name="adresas" value="<?=$usersdata["adresas"]?>"><?=$usersdata["adresas"]?> <span class="glyphicon glyphicon-edit" onclick="this.previousElementSibling.type = 'text'; this.parentElement.innerHTML = this.previousElementSibling.outerHTML; return false; "></span>
                </td> 
              </tr>
               <tr>
                <td align="right">Zip code:
                </td> 
                   <td><input type="hidden" class="form-control" placeholder="Zip code" autofocus="" name="pasto_kodas" value="<?=$usersdata["pasto_kodas"]?>"><?=$usersdata["pasto_kodas"]?> <span class="glyphicon glyphicon-edit" onclick="this.previousElementSibling.type = 'text'; this.parentElement.innerHTML = this.previousElementSibling.outerHTML; return false; "></span>
              </tr>
               <tr>
                <td align="right">Personal ID:
                </td> 
                   <td><?=$usersdata["asmens_kodas"]?> To change your Passport ID you need <a href="/contact/">Contact us </a>
                </td> 
              </tr>
                 <tr>
                <td align="right">Your IP:
                </td> 
                   <td><?=$usersdata["ip_address"]?>
                </td> 
              </tr>
              <tr>
                <td align="right">Profile Created On:
                </td> 
                   <td><?=$usersdata["create_date"]?>
                </td> 
              </tr>
             </tbody> 
            </table> 
            <button class="btn btn-lg btn-login btn-block about" type="submit" onclick="if(confirm('Are you sure?')) {updateUser(); return false;}">Save changes</button>
          </div>

          <h1 id="tables" class="page-header h1profile" onclick="showblock('works');" style="background-color: #FBF8EF; padding: 2px;">
            My uploaded works:
          </h1>

          <div class="bs-example" id="works" style="display: none; background-color: #FBF8EF; padding: 2px;">
              <table class="table">
         <tbody>   
          <tr> 
          <td colspan="2" class="tdworks">
           
          <?php 

          $finished_works = getFinishedWorks(); 

           ?>

   <div class="works-container-basic upload-list">   

         <?php

         foreach($finished_works as $one_work):  
          
          ?>

   <div class="container fin-work-data">
    <span class="glyphicon glyphicon-edit" title="Edit" onclick="ChangesFinWorksData(this);"></span><span title="Update" onclick="UpdateFinWorksData(this);"></span>
    <!--<h3>Uploaded finished work #1:</h3>-->
    <img class="loading" src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/loading.gif" />
    <form method="post" role="form" enctype="multipart/form-data" onsubmit="if(confirm('Are you sure?')) { loadNewFinishedWorks(this, 'finished_works'); removeWork(this); return false;}">
      <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
      <input type="hidden" name="work_id" value="<?=$one_work["id"]?>" />
    <div class="input-group">
        <div class="result"><img src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/files/thumb/finished/<?=$one_work["user_id"]?>/<?=$one_work["work_img"]?>" height="170" width="132" onclick="openMagnify(this.src, 'finished_works');"></div>
        <textarea class="form-control f-work-description" name="description" placeholder="Description" readonly><?=$one_work["description"]?></textarea>
        <?php

         if($one_work["year"] != 0) 

        ?>
         <select class="form-control f-work-years" disabled>
        <?=($one_work["year"] == 0 ? '<option selected disabled>Select year</option>'.implode("\n\r", $issilavyears) : '<option value="'.$one_work["year"].'">'.$one_work["year"].'</option>'.filter_fin_years($one_work["year"])); ?>
        </select>
      </div> 
       
    <button class="btn btn-danger btn-sm button-addmore-remove rem" type="submit" style="margin-top:5px;">Remove this work</button>
            
    </form>
    <div class="text-muted message"></div>
  </div>

 <?php endforeach; ?>
 
 </div>  

   <div class="works-container-basic to-upload" <?=(count($finished_works) == 5 ? 'style="display:none"' : '')?>>  
          <div class="container">
    <!--<h3>Add finished work #<?=(count($finished_works) == 0 ? 1: count($finished_works) + 1) ?></h3>-->
    <h3>Add finished work</h3>
    <p>Max. works: <strong>5</strong>
    <p>Max. filesize: <strong>4 MB</strong><br />Allowed file extensions: <strong>jpg, jpeg, gif, png</strong></p>
    <img class="loading" src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/loading.gif" />
    <form method="post" role="form" enctype="multipart/form-data" onsubmit="loadNewFinishedWorks(this, 'finished_works'); removeWork(this); return false;">
      <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
    <div class="input-group">
        <input type="file" class="form-control input-sm fileToUpload" name="fileToUpload"/><div class="result"></div>
        <textarea class="form-control" placeholder="Description" name="finished_desc"></textarea>
        <select class="form-control" name="finished_year">
        <option selected disabled>Select year</option>
        <?php echo implode("\n\r", $issilavyears); ?>
        </select>
          <button type="submit" class="btn btn-primary btn-sm" style="margin-top:5px;">Upload this work</button>
      </div>          
    </form>
    <div class="text-muted message"></div>
        <!--<div class="input-group">  
    <button class="btn btn-primary btn-sm button-addmore" style="margin-top:5px;" onclick="addMoreWorks(this);">Add one more</button>
      </div>-->
      <!--<div class="input-group">
    <button class="btn btn-danger btn-sm button-addmore-remove" type="button" style="margin-top:5px;display:none;" onclick="if(confirm('Are you sure?')) removeWork(this);">Remove this work</button>
      </div>-->
  </div>
 </div>   

  </tr>
             </tbody> 
            </table> 
          </div>

          <h1 id="tables" class="page-header h1profile" onclick="showblock('docs'); return false;" style="background-color: #FAFAFA; padding: 2px;">
            My ID:
          </h1>
           <div class="bs-example" id="docs" style="display: none;background-color: #FAFAFA; padding: 2px;">

          <nav class="navbar navbar-default navdocs">
          <div class="container-fluid">
          <ul class="nav navbar-nav">
          <li class="active"><a href="#" onclick="hide_all_docs_tr(); $(this).parent().addClass('active'); $('#tr-pass').show(); return false;">Your passport</a></li>
          <li><a href="#" onclick="hide_all_docs_tr(); $(this).parent().addClass('active'); $('#tr-driver-license').show(); return false;">Driver license</a></li>    
          <!--<li><a href="#" onclick="$('.navdocs li.active').removeClass('active'); $(this).parent().addClass('active'); $('.upload-docs').hide(); $('.uploaded-docs').show(); return false;">Your uploaded documents</a></li>-->
          </ul>
          </div>
          </nav>
            <table class="table upload-docs">
              <tbody> 
               <tr id="tr-pass"> 
          <td colspan="2" class="tddocs">

  <div class="popup-gallery">
  </div>   
           
  <div class="docs-container-basic docs-upload-list">   
  
  <?php

   $passport = getDocs(); 

   if(count($passport)> 0):  

   if(NULL!== $passport["passport_front_side"]):

   ?>
   <div class="container">
    <!--<h3>Uploaded finished work #1:</h3>-->
    <img class="loading" src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/loading.gif" />
    <form method="post" role="form" enctype="multipart/form-data" onsubmit="if(confirm('Are you sure?')) { loadNewFinishedWorks(this, 'pers_docs'); removeWork(this); return false;}">
      <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
      <input type="hidden" name="passport_id" value="<?=$passport["id"]?>" />

      <div class="input-group"> 
      <h3>Your passport front side:</h3>
          <div class="result"><img src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/files/thumb/a_pasas_1/<?=$passport["user_id"]?>/<?=$passport["passport_front_side"]?>" height="204" width="325" onclick="openMagnify(this.src, 'docs');"></div>
      </div> 
    <!--<button class="btn btn-danger btn-sm pdocs-button" type="submit" style="margin-top:5px;">Remove this doc</button>-->
    </form>
    <div class="text-muted message"></div>
  </div>

<?php endif;

 if(NULL !== $passport["passport_back_side"]): ?>

  <div class="container">
    <!--<h3>Uploaded finished work #1:</h3>-->
    <img class="loading" src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/loading.gif" />
    <form method="post" role="form" enctype="multipart/form-data" onsubmit="if(confirm('Are you sure?')) { loadNewFinishedWorks(this, 'pers_docs'); removeWork(this); return false;}">
      <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
      <input type="hidden" name="passport_id" value="<?=$passport["id"]?>" />

      <div class="input-group"> 
      <h3>Your passport back side:</h3>
          <div class="result"><img src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/files/thumb/a_pasas_2/<?=$passport["user_id"]?>/<?=$passport["passport_back_side"]?>" height="204" width="325" onclick="openMagnify(this.src);"></div>
      </div> 
    <!--<button class="btn btn-danger btn-sm pdocs-button" type="submit" style="margin-top:5px;">Select other</button>-->
    </form>
    <div class="text-muted message"></div>
  </div>

 <?php endif; 

 if($passport["passport_year_issue"] != 0): ?>

   <div class="container docs-dates">
    <!--<h3>Uploaded finished work #1:</h3>-->
    <img class="loading" src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/loading.gif" />
    <form method="post" role="form" enctype="multipart/form-data" onsubmit="loadNewFinishedWorks(this, 'pers_docs_dates'); return false;">
      <input type="hidden" name="side" value="dates"/>
      <div class="input-group"> 
      <h3>Select expiry:</h3>
      </div> 

     <?php

     $docs_year_disabled = "";
     $docs_month_iss_disabled = "";
     $docs_year_exp_disabled = "";
     $docs_month_exp_disabled = "";

     if(NULL === $passport["passport_year_expired"]) 
     $docs_year_iss_disabled = "disabled";

     if(NULL === $passport["passport_month_issue"]) 
     $docs_month_iss_disabled = "disabled";

     if(NULL === $passport["passport_year_expired"]) 
     $docs_year_exp_disabled = "disabled";

     if(NULL === $passport["passport_month_expired"]) 
     $docs_month_exp_disabled = "disabled";

    ?>

    <select class="form-control f-pdocs-expiredyear" name="passport_year_issue" <?=$docs_year_iss_disabled?>>
       <?=($passport["passport_year_issue"] == 0 ? '<option selected disabled>Year of issue</option>'.implode("\n\r", $pasport_issue_years) : '<option value="'.$passport["passport_year_issue"].'">'.$passport["passport_year_issue"].'</option>'); ?>
    </select>
    <select class="form-control f-pdocs-expiredyear" name="passport_month_issue" <?=$docs_month_iss_disabled?>> 
         
       <?php if($passport["passport_month_issue"] == 0):?>

        <option selected disabled>Month of issues</option>

       <?php foreach($menesiai as $menuo) : ?>
          <option value="<?=$menuo?>"<?=$passport["passport_month_issue"] && $menuo == $passport["passport_month_issue"] ?  'selected': '' ?>><?=$menuo?></option>
       <?php endforeach; else: ?>
          <option value="<?=((int)$passport["passport_month_issue"] < 10) ? "0".$passport["passport_month_issue"] : (int)$passport["passport_month_issue"];?>"><?=((int)$passport["passport_month_issue"] < 10) ? "0".$passport["passport_month_issue"] : (int)$passport["passport_month_issue"];?></option>
       <?php endif;?>

    </select> 
    <select class="form-control f-pdocs-expiredyear" name="passport_year_expired" <?=$docs_year_exp_disabled?>>
       <?=($passport["passport_year_expired"] == 0 ? '<option selected disabled>Year of expiry</option>'.implode("\n\r", $pasport_expiry_years) : '<option value="'.$passport["passport_year_expired"].'">'.$passport["passport_year_expired"].'</option>'); ?>
    </select>
    <select class="form-control f-pdocs-expiredyear" name="passport_month_expired" <?=$docs_month_exp_disabled?>>
          
       <?php if($passport["passport_month_expired"] == 0):?>

        <option selected disabled>Month of expiry</option>

        <?php foreach($menesiai as $menuo) : ?>
          <option value="<?=$menuo?>" <?=$passport["passport_month_expired"] && $menuo == $passport["passport_month_expired"] ?  'selected': '' ?>><?=$menuo?></option>
       <?php endforeach; else: ?>
            <option value="<?=((int)$passport["passport_month_expired"] < 10) ? "0".$passport["passport_month_expired"] : (int)$passport["passport_month_expired"];?>"><?=((int)$passport["passport_month_expired"] < 10) ? "0".$passport["passport_month_expired"] : (int)$passport["passport_month_expired"];?></option>
       <?php endif;?>

    </select>
     <button class="btn btn-danger btn-sm pdocs-button" type="submit" style="margin-top:5px;" onclick="if(confirm('If you confirm, old passport front and backside will be removed!')) loadNewFinishedWorks(this, 'pers_docs_dates'); return false;">Upload new doc</button>  
     <button class="btn btn-danger btn-sm pdocs-button" type="submit" style="margin-top:5px;" >Confirm dates</button>        
    </form>
    <div class="text-muted message"></div>
  </div> 

<?php endif;endif; ?>
 </div>  

  <?php

   $doc_front_block = "";
   $doc_back_block = "style='display:none'";
   $doc_dates_block = "style='display:none'";
   
   if(NULL !== $passport["passport_front_side"])
   $doc_front_block = "style='display:none'"; 

   if(NULL === $passport["passport_back_side"] && NULL !== $passport["passport_front_side"])
   $doc_back_block = ""; 

   if($passport["passport_year_issue"] == 0 && NULL !== $passport["passport_back_side"])
   $doc_dates_block = ""; 

  ?>

  <div class="docs-container-basic frontdoc-to-upload" <?=$doc_front_block?>>  
          <div class="container">
    <!--<h3>Add finished work #<?=(count($finished_works) == 0 ? 1: count($finished_works) + 1) ?></h3>-->
    
    <img class="loading" src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/loading.gif" />
    <form method="post" role="form" enctype="multipart/form-data" onsubmit="loadNewFinishedWorks(this, 'pers_docs'); removeWork(this); return false;">
      <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
    <div class="input-group">
    <h3>Select passport front side</h3>
    <p>Max. filesize: <strong>4 MB</strong><br />Allowed file extensions: <strong>jpg, jpeg, gif, png</strong></p>
        <input type="file" class="form-control input-sm fileToUpload" name="fileToUpload"/>
        <input type="hidden" class="form-control input-sm" name="side" value="front"/>
        <div class="result"></div>
          <button type="submit" class="btn btn-primary btn-sm" style="margin-top:5px;">Upload</button>
      </div>          
    </form>
    <div class="text-muted message"></div>
        <!--<div class="input-group">  
    <button class="btn btn-primary btn-sm button-addmore" style="margin-top:5px;" onclick="addMoreWorks(this);">Add one more</button>
      </div>-->
      <!--<div class="input-group">
    <button class="btn btn-danger btn-sm button-addmore-remove" type="button" style="margin-top:5px;display:none;" onclick="if(confirm('Are you sure?')) removeWork(this);">Remove this work</button>
      </div>-->
  </div>
 </div>

   <div class="docs-container-basic backdoc-to-upload" <?=$doc_back_block?>>  
          <div class="container">
    <!--<h3>Add finished work #<?=(count($finished_works) == 0 ? 1: count($finished_works) + 1) ?></h3>-->
    
    <img class="loading" src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/loading.gif" />
    <form method="post" role="form" enctype="multipart/form-data" onsubmit="loadNewFinishedWorks(this, 'pers_docs'); removeWork(this); return false;">
      <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
    <div class="input-group">
    <h3>Select passport back side</h3>
    <p>Max. filesize: <strong>4 MB</strong><br />Allowed file extensions: <strong>jpg, jpeg, gif, png</strong></p>
        <input type="file" class="form-control input-sm fileToUpload" name="fileToUpload"/>
        <input type="hidden" class="form-control input-sm" name="side" value="back"/>
        <div class="result"></div>
          <button type="submit" class="btn btn-primary btn-sm" style="margin-top:5px;">Upload</button>
      </div>          
    </form>
    <div class="text-muted message"></div>
        <!--<div class="input-group">  
    <button class="btn btn-primary btn-sm button-addmore" style="margin-top:5px;" onclick="addMoreWorks(this);">Add one more</button>
      </div>-->
      <!--<div class="input-group">
    <button class="btn btn-danger btn-sm button-addmore-remove" type="button" style="margin-top:5px;display:none;" onclick="if(confirm('Are you sure?')) removeWork(this);">Remove this work</button>
      </div>-->
  </div>
 </div>

   <div class="docs-container-basic issuesdocs-to-upload" <?=$doc_dates_block?>>   
   <div class="container">
    <!--<h3>Uploaded finished work #1:</h3>-->
    <img class="loading" src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/loading.gif" />
    <form method="post" role="form" enctype="multipart/form-data" onsubmit="if(confirm('Are you sure?')) { loadNewFinishedWorks(this, 'pers_docs_dates'); removeWork(this); return false;}">
      <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
      <input type="hidden" name="side" value="dates"/>
      <div class="input-group"> 
      <h3>Select expiry:</h3>
      </div>  

     <?php

     $docs_year_disabled = "";
     $docs_month_iss_disabled = "";
     $docs_year_exp_disabled = "";
     $docs_month_exp_disabled = "";

     if(NULL === $passport["passport_year_expired"]) 
     $docs_year_iss_disabled = "disabled";

     if(NULL === $passport["passport_month_issue"]) 
     $docs_month_iss_disabled = "disabled";

     if(NULL === $passport["passport_year_expired"]) 
     $docs_year_exp_disabled = "disabled";

     if(NULL === $passport["passport_month_expired"]) 
     $docs_month_exp_disabled = "disabled";

    ?>

     <select class="form-control f-pdocs-expiredyear" name="passport_year_issue" <?=$docs_year_iss_disabled?>>
       <?=($passport["passport_year_issue"] == 0 ? '<option selected disabled>Year of issue</option>'.filter_fin_years(false, 'pass') : '<option value="'.$passport["passport_year_issue"].'">'.$passport["passport_year_issue"].'</option>'.filter_fin_years($passport["passport_year_issue"])); ?>
    </select>
    <select class="form-control f-pdocs-expiredyear" name="passport_month_issue" <?=$docs_month_iss_disabled?>> 
         
       <?php if($passport["passport_month_issue"] == 0):?>

        <option selected disabled>Month of issues</option>

       <?php foreach($menesiai as $menuo) : ?>
          <option value="<?=$menuo?>"<?=$passport["passport_month_issue"] && $menuo == $passport["passport_month_issue"] ?  'selected': '' ?>><?=$menuo?></option>
       <?php endforeach; else: ?>
          <option value="<?=((int)$passport["passport_month_issue"] < 10) ? "0".$passport["passport_month_issue"] : (int)$passport["passport_month_issue"];?>"><?=((int)$passport["passport_month_issue"] < 10) ? "0".$passport["passport_month_issue"] : (int)$passport["passport_month_issue"];?></option>
       <?php endif;?>

    </select> 
    <select class="form-control f-pdocs-expiredyear" name="passport_year_expired" <?=$docs_year_exp_disabled?>>
       <?=($passport["passport_year_expired"] == 0 ? '<option selected disabled>Year of expiry</option>'.filter_fin_years(false, 'pass') : '<option value="'.$passport["passport_year_expired"].'">'.$passport["passport_year_expired"].'</option>'.filter_fin_years($passport["passport_year_expired"])); ?>
    </select>
    <select class="form-control f-pdocs-expiredyear" name="passport_month_expired" <?=$docs_month_exp_disabled?>>
          
       <?php if($passport["passport_month_expired"] == 0):?>

        <option selected disabled>Month of expiry</option>

        <?php foreach($menesiai as $menuo) : ?>
          <option value="<?=$menuo?>" <?=$passport["passport_month_expired"] && $menuo == $passport["passport_month_expired"] ?  'selected': '' ?>><?=$menuo?></option>
       <?php endforeach; else: ?>
            <option value="<?=((int)$passport["passport_month_expired"] < 10) ? "0".$passport["passport_month_expired"] : (int)$passport["passport_month_expired"];?>"><?=((int)$passport["passport_month_expired"] < 10) ? "0".$passport["passport_month_expired"] : (int)$passport["passport_month_expired"];?></option>
       <?php endif;?>

    </select>
     <button class="btn btn-danger btn-sm pdocs-button" style="margin-top:5px;" onclick="if(confirm('If you confirm, old passport front and backside will be removed!')) { console.log(this); return false;}">Upload new doc</button>  
     <button class="btn btn-danger btn-sm pdocs-button" type="submit" style="margin-top:5px;">Confirm dates</button>        
    </form>
    <div class="text-muted message"></div>
  </div>
 </div>  
</td>
  </tr>

    <tr id="tr-driver-license" style="display: none;"> 
          <td colspan="2" class="tddriver">
           
  <div class="docs-container-basic docs-upload-list">   
  
  <?php

  if(count($passport)> 0): 

  if(NULL !== $passport["driver_front_side"]) :

   ?>

   <div class="container">
    <!--<h3>Uploaded finished work #1:</h3>-->
    <img class="loading" src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/loading.gif" />
    <form method="post" role="form" enctype="multipart/form-data" onsubmit="if(confirm('Are you sure?')) { loadNewFinishedWorks(this, 'driver'); removeWork(this); return false;}">
      <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
      <input type="hidden" name="driver_id" value="<?=$passport["id"]?>" />

      <div class="input-group"> 
      <h3>Your driver license front side:</h3>
          <div class="result"><img src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/files/thumb/driver_1/<?=$passport["user_id"]?>/<?=$passport["driver_front_side"]?>" height="204" width="325"></div>
      </div> 
    <!--<button class="btn btn-danger btn-sm pdocs-button" type="submit" style="margin-top:5px;">Remove this doc</button>-->
    </form>
    <div class="text-muted message"></div>
  </div>

<?php endif; 
if(NULL !== $passport["driver_back_side"]) :
?>

  <div class="container">
    <!--<h3>Uploaded finished work #1:</h3>-->
    <img class="loading" src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/loading.gif" />
    <form method="post" role="form" enctype="multipart/form-data" onsubmit="if(confirm('Are you sure?')) { loadNewFinishedWorks(this, 'driver'); removeWork(this); return false;}">
      <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
      <input type="hidden" name="driver_id" value="<?=$passport["id"]?>" />

      <div class="input-group"> 
      <h3>Your driver license back side:</h3>
          <div class="result"><img src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/files/thumb/driver_2/<?=$passport["user_id"]?>/<?=$passport["driver_back_side"]?>" height="204" width="325"></div>
      </div> 
    <!--<button class="btn btn-danger btn-sm pdocs-button" type="submit" style="margin-top:5px;">Select other</button>-->
    </form>
    <div class="text-muted message"></div>
  </div>

<?php endif;
 if($passport["driver_year_issue"] != 0) : ?>

   <div class="container docs-dates">
    <!--<h3>Uploaded finished work #1:</h3>-->
    <img class="loading" src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/loading.gif" />
    <form method="post" role="form" enctype="multipart/form-data" onsubmit="loadNewFinishedWorks(this, 'driver_dates'); return false;">
      <input type="hidden" name="side" value="dates"/>
      <div class="input-group"> 
      <h3>Select expiry:</h3>
      </div> 

     <?php

     $docs_year_disabled = "";
     $docs_month_iss_disabled = "";
     $docs_year_exp_disabled = "";
     $docs_month_exp_disabled = "";

     if(NULL === $passport["driver_year_expired"]) 
     $docs_year_iss_disabled = "disabled";

     if(NULL === $passport["driver_month_issue"]) 
     $docs_month_iss_disabled = "disabled";

     if(NULL === $passport["driver_year_expired"]) 
     $docs_year_exp_disabled = "disabled";

     if(NULL === $passport["driver_month_expired"]) 
     $docs_month_exp_disabled = "disabled";

    ?>

    <select class="form-control f-pdocs-expiredyear" name="passport_year_issue" <?=$docs_year_iss_disabled?>>
       <?=($passport["driver_year_issue"] == 0 ? '<option selected disabled>Year of issue</option>'.filter_fin_years(false, 'pass') : '<option value="'.$passport["driver_year_issue"].'">'.$passport["driver_year_issue"].'</option>'.filter_fin_years($passport["driver_year_issue"])); ?>
    </select>
    <select class="form-control f-pdocs-expiredyear" name="driver_month_issue" <?=$docs_month_iss_disabled?>> 
         
       <?php if($passport["driver_month_issue"] == 0):?>

        <option selected disabled>Month of issues</option>

       <?php foreach($menesiai as $menuo) : ?>
          <option value="<?=$menuo?>"<?=$passport["driver_month_issue"] && $menuo == $passport["driver_month_issue"] ?  'selected': '' ?>><?=$menuo?></option>
       <?php endforeach; else: ?>
          <option value="<?=((int)$passport["driver_month_issue"] < 10) ? "0".$passport["driver_month_issue"] : (int)$passport["driver_month_issue"];?>"><?=((int)$passport["driver_month_issue"] < 10) ? "0".$passport["driver_month_issue"] : (int)$passport["driver_month_issue"];?></option>
       <?php endif;?>

    </select> 
    <select class="form-control f-pdocs-expiredyear" name="driver_year_expired" <?=$docs_year_exp_disabled?>>
       <?=($passport["driver_year_expired"] == 0 ? '<option selected disabled>Year of expiry</option>'.filter_fin_years(false, 'pass') : '<option value="'.$passport["driver_year_expired"].'">'.$passport["driver_year_expired"].'</option>.'.filter_fin_years($passport["driver_year_expired"])); ?>
    </select>
    <select class="form-control f-pdocs-expiredyear" name="driver_month_expired" <?=$docs_month_exp_disabled?>>
          
       <?php if($passport["driver_month_expired"] == 0):?>

        <option selected disabled>Month of expiry</option>

        <?php foreach($menesiai as $menuo) : ?>
          <option value="<?=$menuo?>" <?=$passport["driver_month_expired"] && $menuo == $passport["driver_month_expired"] ?  'selected': '' ?>><?=$menuo?></option>
       <?php endforeach; else: ?>
            <option value="<?=((int)$passport["driver_month_expired"] < 10) ? "0".$passport["driver_month_expired"] : (int)$passport["driver_month_expired"];?>"><?=((int)$passport["driver_month_expired"] < 10) ? "0".$passport["driver_month_expired"] : (int)$passport["driver_month_expired"];?></option>
       <?php endif;?>

    </select>
     <button class="btn btn-danger btn-sm pdocs-button" type="submit" style="margin-top:5px;" onclick="if(confirm('If you confirm, old driver license front and backside will be removed!')) loadNewFinishedWorks(this, 'driver_dates'); return false;">Upload new doc</button>  
     <button class="btn btn-danger btn-sm pdocs-button" type="submit" style="margin-top:5px;" >Confirm dates</button>        
    </form>
    <div class="text-muted message"></div>
  </div> 

<?php endif;endif; ?>
 </div>  

  <?php

   $doc_front_block = "";
   $doc_back_block = "style='display:none'";
   $doc_dates_block = "style='display:none'";
   
   if(NULL !== $passport["driver_front_side"])
   $doc_front_block = "style='display:none'"; 

   if(NULL === $passport["driver_back_side"] && NULL !== $passport["driver_front_side"])
   $doc_back_block = ""; 

   if($passport["driver_year_issue"] == 0 && NULL !== $passport["driver_back_side"])
   $doc_dates_block = ""; 

  ?>

  <div class="docs-container-basic frontdoc-to-upload" <?=$doc_front_block?>>  
          <div class="container">
    <!--<h3>Add finished work #<?=(count($finished_works) == 0 ? 1: count($finished_works) + 1) ?></h3>-->
    
    <img class="loading" src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/loading.gif" />
    <form method="post" role="form" enctype="multipart/form-data" onsubmit="loadNewFinishedWorks(this, 'driver'); removeWork(this); return false;">
      <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
    <div class="input-group">
    <h3>Select driver license front side</h3>
    <p>Max. filesize: <strong>4 MB</strong><br />Allowed file extensions: <strong>jpg, jpeg, gif, png</strong></p>
        <input type="file" class="form-control input-sm fileToUpload" name="fileToUpload"/>
        <input type="hidden" class="form-control input-sm" name="side" value="front"/>
        <div class="result"></div>
          <button type="submit" class="btn btn-primary btn-sm" style="margin-top:5px;">Upload</button>
      </div>          
    </form>
    <div class="text-muted message"></div>
        <!--<div class="input-group">  
    <button class="btn btn-primary btn-sm button-addmore" style="margin-top:5px;" onclick="addMoreWorks(this);">Add one more</button>
      </div>-->
      <!--<div class="input-group">
    <button class="btn btn-danger btn-sm button-addmore-remove" type="button" style="margin-top:5px;display:none;" onclick="if(confirm('Are you sure?')) removeWork(this);">Remove this work</button>
      </div>-->
  </div>
 </div>

   <div class="docs-container-basic backdoc-to-upload" <?=$doc_back_block?>>  
          <div class="container">
    <!--<h3>Add finished work #<?=(count($finished_works) == 0 ? 1: count($finished_works) + 1) ?></h3>-->
    
    <img class="loading" src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/loading.gif" />
    <form method="post" role="form" enctype="multipart/form-data" onsubmit="loadNewFinishedWorks(this, 'driver'); removeWork(this); return false;">
      <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
    <div class="input-group">
    <h3>Select driver license back side</h3>
    <p>Max. filesize: <strong>4 MB</strong><br />Allowed file extensions: <strong>jpg, jpeg, gif, png</strong></p>
        <input type="file" class="form-control input-sm fileToUpload" name="fileToUpload"/>
        <input type="hidden" class="form-control input-sm" name="side" value="back"/>
        <div class="result"></div>
          <button type="submit" class="btn btn-primary btn-sm" style="margin-top:5px;">Upload</button>
      </div>          
    </form>
    <div class="text-muted message"></div>
        <!--<div class="input-group">  
    <button class="btn btn-primary btn-sm button-addmore" style="margin-top:5px;" onclick="addMoreWorks(this);">Add one more</button>
      </div>-->
      <!--<div class="input-group">
    <button class="btn btn-danger btn-sm button-addmore-remove" type="button" style="margin-top:5px;display:none;" onclick="if(confirm('Are you sure?')) removeWork(this);">Remove this work</button>
      </div>-->
  </div>
 </div>

   <div class="docs-container-basic issuesdocs-to-upload" <?=$doc_dates_block?>>   
   <div class="container">
    <!--<h3>Uploaded finished work #1:</h3>-->
    <img class="loading" src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/loading.gif" />
    <form method="post" role="form" enctype="multipart/form-data" onsubmit="if(confirm('Are you sure?')) { loadNewFinishedWorks(this, 'driver'); removeWork(this); return false;}">
      <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
      <input type="hidden" name="side" value="dates"/>
      <div class="input-group"> 
      <h3>Select expiry:</h3>
      </div>    
    <select class="form-control f-pdocs-expiredyear" name="driver_year_issue">
      <option selected disabled>Year of issue</option>
      <?php echo implode("\n\r", $issilavyears);  ?>
    </select>
    <select class="form-control f-pdocs-expiredyear" name="driver_month_issue">
       <option selected disabled>Month of issue</option>
       <?php echo implode("\n\r", $issilavyears);  ?>
    </select> 
    <select class="form-control f-pdocs-expiredyear" name="driver_year_expired">
      <option selected disabled>Year of expiry</option>
      <?php echo implode("\n\r", $issilavyears);  ?>
    </select>
    <select class="form-control f-pdocs-expiredyear" name="driver_month_expired">
       <option selected disabled>Month of expiry</option>
       <?php echo implode("\n\r", $issilavyears);  ?>
    </select>
     <button class="btn btn-danger btn-sm pdocs-button" type="submit" style="margin-top:5px;" onclick="if(confirm('If you confirm, old passport front and backside will be removed!')) {console.log(1111); return false;}">Upload new doc</button>  
     <button class="btn btn-danger btn-sm pdocs-button" type="submit" style="margin-top:5px;">Confirm dates</button>        
    </form>
    <div class="text-muted message"></div>
  </div>
 </div>  
</td>
  </tr>


  <!--<tr id="tr-driver-license" style="display: none;"> 
    <td colspan="2" class="tddriver">
     
    <?php 

    $finished_works = getFinishedWorks(); 

     ?>

  <div class="works-container-basic upload-list">   

   <?php

   foreach($finished_works as $one_work):  
    
    ?>

   <div class="container" style="display: inline-block;">
    <img class="loading" src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/loading.gif" />
    <form method="post" role="form" enctype="multipart/form-data" onsubmit="if(confirm('Are you sure?')) { loadNewFinishedWorks(this, 'finished_works'); removeWork(this); return false;}">
      <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
      <input type="hidden" name="work_id" value="<?=$one_work["id"]?>" />
    <div class="input-group">
        <div class="result"><img src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/files/thumb/finished/<?=$one_work["user_id"]?>/<?=$one_work["work_img"]?>" height="170" width="132"></div>
        <textarea class="form-control" placeholder="Description" readonly><?=$one_work["description"]?></textarea>
        <?php

         if($one_work["year"] != 0) : ?> <select class="form-control" disabled>
        <option value="<?=$one_work["year"]?>"><?=$one_work["year"]?></option>
        </select>
        <?php endif;?>
      </div> 
       
    <button class="btn btn-danger btn-sm button-addmore-remove rem" type="submit" style="margin-top:5px;">Remove this doc</button>
            
    </form>
    <div class="text-muted message"></div>
  </div>

 <?php endforeach; ?>
 
 </div>  

   <div class="works-container-basic to-upload">  
          <div class="container">
    <h3>Add driver license doc</h3>
    <p>Max. filesize: <strong>4 MB</strong><br />Allowed file extensions: <strong>jpg, jpeg, gif, png</strong></p>
    <img class="loading" src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/loading.gif" />
    <form method="post" role="form" enctype="multipart/form-data" onsubmit="loadNewFinishedWorks(this, 'finished_works'); removeWork(this); return false;">
      <input type="hidden" name="MAX_FILE_SIZE" value="4194304" />
    <div class="input-group">
        <input type="file" class="form-control input-sm fileToUpload" name="fileToUpload"/><div class="result"></div>
          <button type="submit" class="btn btn-primary btn-sm" style="margin-top:5px;">Upload this doc</button>
      </div>          
    </form>
    <div class="text-muted message"></div>
        <div class="input-group">  
    <button class="btn btn-primary btn-sm button-addmore" style="margin-top:5px;" onclick="addMoreWorks(this);">Add one more</button>
      </div>
      <div class="input-group">
    <button class="btn btn-danger btn-sm button-addmore-remove" type="button" style="margin-top:5px;display:none;" onclick="if(confirm('Are you sure?')) removeWork(this);">Remove this work</button>
      </div>
  </div>
 </div>    
  </tr>-->       
</tbody> 
</table>
  <?php $docs = getDocs(); ?>
  </div>

          <h1 id="tables" class="page-header h1profile" onclick="showblock('bank'); return false;" style="background-color: #FAFAFA; padding: 2px;">
            Bank information:
          </h1>

         <div class="bs-example" id="bank" style="display: none;background-color: #FAFAFA; padding: 2px;">

          <?php 

          $bankinfo = count(getBankInfo()) > 0 ? getBankInfo() : array(array("city" => "", "name" => "", "account" => "")); 

          $i = 0;
               
          foreach($bankinfo as $bank):  
             
          $i++;

          ?>
           <div class="container bank">  
            <h4><?=($i==1 ? "Main bank # ".$i: 'Additional bank # '.$i) ?></h4>
           <table class="table">
              <tbody>
              <tr>
                <td>City:
                </td> 
                   <td>
                      <input type="text" name="city_of_bank" value="<?=$bank["city"] ?>" class="form-control" placeholder="City">
                 </td> 
              </tr>
               <tr>
                <td>Bank name:
                </td> 
                 <td>
                      <input name="bankname" type="text" value="<?=$bank["name"] ?>" class="form-control" placeholder="Name">
                 </td> 
              </tr>
              <tr>
                <td>Account number:
                </td> 
                 <td>
                      <input name="account_number" type="text" value="<?=$bank["account"] ?>" class="form-control" placeholder="Account number">
                 </td> 
              </tr>
             </tbody> 
            </table>
                  <button class="btn btn-danger removebank" onclick="removeBank(this, 'banklist'); return false;" style="<?=($i>=2 ? 'display:block' : 'display:none'); ?>;margin-bottom:5px;">Remove this bank</button>
                  <?php if(count($bankinfo) == $i) : ?><button class="btn btn-lg btn-login btn-block docs add-additional-bank" type="submit" onclick="addOtherBank(this); return false;">Add additional</button><?php endif; ?>
             
                  <?php if($i>=count($bankinfo)) :?><button class="btn btn-lg btn-login btn-block docs save-changes-bank" type="submit" onclick="if(confirm('Are you sure?')) {updateBank(); return false;}">Save changes</button> <?php endif;?>
                </div>  

          <?php endforeach; ?>

          </div>

          <h1 id="tables" class="page-header h1profile" onclick="showblock('close-relative-block'); return false;" style="background-color: #FAFAFA; padding: 2px;">
            Close relatives:
          </h1>
          
          <div class="bs-example" id="close-relative-block" style="display: none;background-color: #FAFAFA; padding: 2px;">
           <?php 

          $relative_info = count(getBankInfo('relative')) > 0 ? getBankInfo('relative') : array(array("name" => "", "surname" => "", "phone" => "")); 

          ?>

          <?php for($i = 0; $i<count($relative_info); $i++):
 
          $relative = $relative_info[$i];

          ?>

          <div class="container close-relative">  
            <h4><?=($i==0 ? "Main close relative # ".($i+1): 'Additional relative # '.($i+1)) ?></h4>

          
           <table class="table">
              <tbody>
              <tr>
                <td>Name:
                </td> 
                   <td>
                      <input type="text" name="relative_name" value="<?=$relative["name"] ?>" class="form-control" placeholder="Relative name">
                 </td> 
              </tr>
               <tr>
                <td>Surname:
                </td> 
                 <td>
                      <input name="relative_surname" type="text" value="<?=$relative["surname"] ?>" class="form-control" placeholder="Relative surname">
                 </td> 
              </tr>
              <tr>
                <td>Telephone number:
                </td> 
                 <td>
                      <input name="relative_phone" type="text" value="<?=$relative["phone"] ?>" class="form-control" placeholder="Telephone">
                 </td> 
              </tr>
             </tbody> 
            </table>   

                  <button class="btn btn-danger removerelative" onclick="removeRelative(this, 'relativelist'); return false;" style="<?=($i>=1 ? 'display:block' : 'display:none'); ?>;margin-bottom:5px;">Remove this relative</button>
                  <?php if(count($relative_info) == ($i+1)) : ?> <button class="btn btn-lg btn-login btn-block relative add-additional-relative" type="submit" onclick="addOtherRelative(this); return false;">Add additional</button> <?php endif;?>
             
                  <?php if(($i+1)>=count($relative_info)) :?> <button class="btn btn-lg btn-login btn-block relative save-changes-relative" type="submit" onclick="if(confirm('Are you sure?')) {updateBank('relative'); return false;}">Save changes</button> <?php endif;?>
                </div> 

              <?php endfor; ?>

             
         </div>         
    <!--container end-->
          <!-- /example -->
        </div>
      </div>
    </div>  
    <script src="<?=WEBSITE.TEMPLATE_FOLDER?>plugins/phonesflags/build/js/intlTelInput.js"></script>
    <script>

    function openCloseMyModalZoom(image){

/*
     // Get the modal
      var imgModal = document.getElementById('imgModal');

      // Get the image and insert it inside the modal - use its "alt" text as a caption
      //var imgZoom = document.getElementById('myImg');
      var modalImg = document.getElementById("img01");
      var captionText = document.getElementById("caption");
      
      var basic_photo = image;

      personal-photo.addEventListener("click", function(){
        imgModal.style.display = "block";
        modalImg.src = this.src;
        captionText.innerHTML = this.alt;
      });

      // Get the <span> element that closes the modal
      var imgclose = document.getElementsByClassName("img-close")[0];

      // When the user clicks on <span> (x), close the modal
      imgclose.onclick = function() { 
        imgModal.style.display = "none";
      }
      */

    }


    function openCloseMyModal(){
      
       // Get the modal
    var modal = document.getElementById('myModal');

    // Get the button that opens the modal
    var btn = document.getElementById("myBtn");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on the button, open the modal 
    btn.onclick = function() {
      modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
      modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
     }
    }

    function setRandomImg(){
       $("img").each(function(key, value){
        if($(this).attr("src") && $(this).attr("src").indexOf('?rand') === -1) $(this).attr("src", $(this).attr("src") + "?rand=" + Math.random());
    })  
    }

    document.addEventListener("DOMContentLoaded", function(event) { 
    
    loadNewFinishedWorks();  

    setRandomImg();

    openCloseMyModal();  

    $('.popup-with-zoom-anim').magnificPopup({
          type: 'inline',

          fixedContentPos: false,
          fixedBgPos: true,

          overflowY: 'auto',

          closeBtnInside: true,
          preloader: false,
          
          midClick: true,
          mainClass: 'my-mfp-zoom-in'
    });

      <?php if(isset($usersdata["metai"])) : ?>
        var selectedyear =  $("select#year");
        getDays('year',selectedyear, true);
      <?php endif; 
       
       if($docs["passport_front_side"] &&  $docs["passport_front_side"] && $docs["passport_back_side"]):
      
      ?>     
         //$(".upload-docs").hide();
         //$(".uploaded-docs").show();

      //$(".docs").find('.navdocs ul > li').removeClass('active');
      //$(".docs").find('.navdocs ul > li:eq(1)').addClass('active');     

      <?php
         endif;
      ?>
    });  


    function setPhone(td){

    var input = td.children[0];

    input.value = '';

    window.intlTelInput(input, {
      // allowDropdown: false,
      // autoHideDialCode: false,
      // autoPlaceholder: "off",
      // dropdownContainer: document.body,
      // excludeCountries: ["us"],
      // formatOnDisplay: false,
      // geoIpLookup: function(callback) {
      //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
      //     var countryCode = (resp && resp.country) ? resp.country : "";
      //     callback(countryCode);
      //   });
      // },
      // hiddenInput: "full_number",
      initialCountry: "lt",
      // localizedCountries: { 'de': 'Deutschland' },
      // nationalMode: false,
      // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
      // placeholderNumberType: "MOBILE",
      // preferredCountries: ['cn', 'jp'],
      // separateDialCode: true,
      utilsScript: "<?=WEBSITE.TEMPLATE_FOLDER?>/plugins/phonesflags/build/js/utils.js",
    });
   }

  var photo_data = {}; 

  /* Action - personal photo, works docs and other actions */

  function setPersonalPhoto(input, action){

      $myModalcontent = $("#myModal").find('.modal-content'); 

      if($(".profile-message").length > 0)
                $(".profile-message").remove()

      var $profile_photo = $("body").find("#img_photo"); 

      var inputfile = $(input)[0].files[0]; 

      if(typeof(inputfile) === 'undefined') return;

      var formData = new FormData();
      formData.append('file', inputfile);
      formData.append('action', 'tmp_personal_photo');

      $.ajax({
            url: '/template/functions/profile.php',
            data:formData,
            type: 'POST',
            dataType : 'json',
            processData: false,
            contentType: false,

            beforeSend: function(data){

             $profile_photo.replaceWith('<img src=\'/template/img/loaders/giphy.gif\' class=\'photo-loader\'>');

            },

            success : function(data){

            var img_src = data.img_src; 
            var img_name = data.img_name;
            window.avatar_img.src = img_src;

            setRandomImg();

            $.extend( photo_data, {img_name: img_name});

            $(".photo-loader").replaceWith($profile_photo); 

            var message = 'Profile photo successfully updated. To finish updating, click on the button!';

		    openMagnifyMessage(message);

            },

           error:function(a, b, c){
           
            console.log(a, b, c);

            },

        });
  }
            var allbuttons = {};

            function bankaiPagalEile(relative = false){

            var i = 1;

            var block = (relative === false ? "div.bank" : "div.close-relative");

            $.each($(block), function(){
               $(block+":eq("+i+")").find('h4').text("Additional "+(block.substr(4).replace("-"," "))+" # "+(i+1));
               i++;
             });
             }

            /* Banklist - grazinam visus mygtukus atgal jeigu yra banku sarasas */

            function removeBank(button, banklist = false){   

            if(banklist !== false && allbuttons['bankbuttons'] === undefined){
               $.extend(allbuttons, { bankbuttons: {
               additional: $(button).next(),
               save_changes:$(button).next().next()
            }})
             }

            
             $(button).parents('div.bank').remove();
             if($("div.bank").find('.add-additional-bank').length == 0){
              $.each(allbuttons.bankbuttons, function(key, value){
               // console.log(value); 
               $(value).appendTo($("div.bank:last"));
             });
             }

             bankaiPagalEile();
            }

            /* Banklist - grazinam visus mygtukus atgal jeigu yra banku sarasas */
            function removeRelative(button, banklist = false){

             if(banklist !== false && allbuttons['relativebuttons'] === undefined){
               $.extend(allbuttons, { relativebuttons: {
               additional: $(button).next(),
               save_changes:$(button).next().next(),
            }})
             }
            
             $(button).parents('div.close-relative').remove();
             if($("div.close-relative").find('.add-additional-ralative').length == 0){

              $.each(allbuttons.relativebuttons, function(key, value){
               $(value).appendTo($("div.close-relative:last"));
             });
             }
             bankaiPagalEile('relative');
            } 

            function addOtherBank(button){
               var banklength = $("div.bank").length;
               if(banklength == 4) {alert('You can not add more than 4 bank!'); return false; }
               var $bank_lasttr = $("div.bank:last"); 
               var $bank_lasttr_clone = $bank_lasttr.clone();
               var $lastnow = $bank_lasttr_clone.insertAfter($bank_lasttr); 
               bankaiPagalEile();  
               $(".removebank").not(':first').css('display','block');
               if(!allbuttons.bankbuttons && banklength == 1) {
                //$.extend(allbuttons, { additional: $('.add-additional-relative:last')});
                //$.extend(allbuttons,{ save_changes: $('.save-changes-relative:last')});
                $.extend(allbuttons, { bankbuttons: {
                additional: $('.add-additional-bank:last'),
                save_changes:$('.save-changes-bank:last')
                } });
                //console.log(allbuttons);
              }
               $('.add-additional-bank').not(':last').remove();
               $('.save-changes-bank').not(':last').remove();

            }

             function addOtherRelative(button){

               var relativelength = $("div.close-relative").length;
               //console.log($(".relative"));
               if(relativelength == 2) {alert('You can not add more than 2 relatives!'); return false; }
               var $bank_lasttr = $("div.close-relative:last"); 
               var $bank_lasttr_clone = $bank_lasttr.clone();
               var $lastnow = $bank_lasttr_clone.insertAfter($bank_lasttr); 
               bankaiPagalEile('relative');  
               $(".removerelative").not(':first').css('display','block');
               if(!allbuttons.relativebuttons && relativelength == 1) {
                //$.extend(allbuttons, { additional: $('.add-additional-relative:last')});
                //$.extend(allbuttons,{ save_changes: $('.save-changes-relative:last')});
                $.extend(allbuttons, { relativebuttons: {
                additional: $('.add-additional-relative:last'),
                save_changes:$('.save-changes-relative:last')
                } });
              }
               $('.add-additional-relative').not(':last').remove();
               $('.save-changes-relative').not(':last').remove();

            }

      function updateDocs(){

      var $update = $("#docs");  
 
      var formData = new FormData();

      formData.append('action', 'docs');
      formData.append('pasasfile_1',$update.find("input[name='a_pasas_1']")[0].files[0]);
      formData.append('pasasfile_2',$update.find("input[name='a_pasas_2']")[0].files[0]);
      formData.append('driverfile',$update.find("input[name='driver']")[0].files[0]);
      formData.append('passport_year_issue',$update.find('select[name=passport_year_issue]').val());
      formData.append('passport_month_issue',$update.find('select[name=passport_month_issue]').val());
      formData.append('passport_expired_year_issue', $update.find('select[name=passport_expired_year_issue]').val());
      formData.append('passport_expired_month_issue', $update.find('select[name=passport_expired_month_issue]').val());
      formData.append('driver_year_issue',$update.find('select[name=driver_year_issue]').val());
      formData.append('driver_month_issue',$update.find('select[name=driver_month_issue]').val());
      formData.append('driver_expired_year_issue',$update.find('select[name=driver_expired_year_issue]').val());
      formData.append('driver_expired_month_issue',$update.find('select[name=driver_expired_month_issue]').val());

      $.ajax({
            url: '/template/functions/profile.php',
            data:formData,
            type: 'POST',
            dataType : 'json',
            processData: false,
            contentType: false,

            beforeSend: function(data){

             $update.replaceWith('<img src=\'/template/img/loaders/giphy.gif\' class=\'photo-loader\'>');

            },

            success : function(data){   

            var pas_front = data.p_front_side;
            var pas_back = data.p_back_side;
            var driver = data.driver_license;
     
            $update.find('.navdocs ul > li').removeClass('active');
            $update.find('.passport-front-side').find('img').attr('src', pas_front ? "<?=WEBSITE.TEMPLATE_FOLDER?>uploads/a_pasas_1/<?=$usersdata["id"]?>/"+pas_front : "<?=WEBSITE.TEMPLATE_FOLDER?>img/avatars/no-image.jpg");
            $update.find('.passport-back-side').find('img').attr('src', pas_back ? "<?=WEBSITE.TEMPLATE_FOLDER?>uploads/a_pasas_2/<?=$usersdata["id"]?>/"+pas_back : "<?=WEBSITE.TEMPLATE_FOLDER?>img/avatars/no-image.jpg");
            $update.find('.driver-license').find('img').attr('src',driver ? "<?=WEBSITE.TEMPLATE_FOLDER?>uploads/driver/<?=$usersdata["id"]?>/"+driver : "<?=WEBSITE.TEMPLATE_FOLDER?>img/avatars/no-image.jpg");
            $update.find('.navdocs ul > li:eq(1)').addClass('active');
            $update.find('.uploaded-docs').show();
            $update.find('.upload-docs').hide();
             
           // var img_src = data.img_src; 
            //var img_name = data.img_name;
            //window.img_photo.src = img_src; 
           // $.extend( photo_data, {img_name: img_name});

            //console.log(data);

          //alert('Changes successfullyy saved. You can continue working with your profile!');
            },
           error:function(a, b, c){
          //$("#myModal p").text('Some errors occurred while updating your profile. Please try again later!');
          //$("#modal-button").click();

            },

            complete : function(b){

            var message = (b && b == 'error' ? 'Some errors occurred while updating your profile. Please try again later!' : 'Your documents successfully uploaded!');
              
            $("body").find('.photo-loader').replaceWith($update);
            $("#myModal p").text(message);
            $("#modal-button").click();
            },

        });
  }

  </script>

  <script type="text/javascript">

  function showRequestWorks(formData, jqForm, options) { 
    var $container = $(jqForm).parents('.works-container-basic .container');
      
    var fileToUploadValue = $container.find($('.fileToUpload')).fieldValue();;

    if (!fileToUploadValue[0] && !$container.find('button.rem')) { 

      $container.find($('.message')).html('You need to select a file!'); 
      return false; 
    }
    $container.find($(".loading")).show();
    return true; 
  } 

  function showRequestDocs(formData, jqForm, options) { 

    var $container = $(jqForm).parents('.docs-container-basic .container');

    var fileToUploadValue = $container.find($('.fileToUpload')).fieldValue();;

    if (!fileToUploadValue[0]) { 

      $container.find($('.message')).html('You need to select a file!'); 
      return false; 
    }

    $container.find($(".loading")).show();
    return true; 
  } 

   function showRequestDocsDates(formData, jqForm, options) { 

    var $container = $(jqForm).parents('.docs-dates');

    $container.find($(".loading")).show();
    return true; 
  } 

  function showResponseWorks(data, statusText, xhr, $form)  {

  	console.log(data);

    var $container = $form.parents('.works-container-basic .container');
    $container.find($('.message')).html('');

    $container.find($(".loading")).hide();
    if (statusText == 'success') {
      var msg = (data.error ? data.error.replace(/##/g, "<br />") : '');
      if (data.img != '' && data.img != 'delete') {
        //$container.find($('.result')).html('<img src="<?=WEBSITE.TEMPLATE_FOLDER?>bootrstap-image-uploader/files/photo/finished/<?=$usersdata["id"]?>/' + data.img + '" />');
        $(data.photo_div).appendTo($(".tdworks > .upload-list"));

        $container.find($('.fileToUpload')).val('');
        $container.find($('textarea')).val('');
        $container.find($('select')).prop('selectedIndex', 0);

        finished_works++;

        console.log(finished_works);

        if(finished_works == 5) $('.to-upload').css('display', 'none');
        else 
        $('.to-upload').css('display', 'block');	

        $container.find($('#formcont')).html('');

        setRandomImg();

      } 
      else if(data.img !='delete') {
        $container.find($('.message')).html(msg); 
      }
    } else {
      $container.find($('.message')).html('Unknown error!'); 
    }
  } 

    function showResponseDocs(data, statusText, xhr, $form)  {

    console.log(data);  

    var $container = $form.parents('.docs-container-basic .container');
    $container.find($('.message')).html('');

    $container.find($(".loading")).hide();
    if (statusText == 'success') {
      var msg = (data.error ? data.error.replace(/##/g, "<br />") : '');
      if (data.img != '' && data.img != 'delete') {
        //$container.find($('.result')).html('<img src="<?=WEBSITE.TEMPLATE_FOLDER?>bootrstap-image-uploader/files/photo/finished/<?=$usersdata["id"]?>/' + data.img + '" />');

        var td = (data.driver === true ? 'driver' : 'docs'); 

        docsinsert = 1;

        $(data.photo_div).appendTo($(".td"+td+"").find(".docs-upload-list"));

         //console.log($container.find(".docs-upload-list"));

        $container.find($('.fileToUpload')).val(''); 

        //$container.find($('textarea')).val('');
        //$container.find($('select')).prop('selectedIndex', 0);
        
        if(data.side == 'front') {
          $(".td"+td+"").find($(".backdoc-to-upload")).show(); 
          $(".td"+td+"").find($(".frontdoc-to-upload")).hide();
          $(".td"+td+"").find($('.docs-dates')).hide(); 
        }
        else if(data.side == 'back'){
          $(".td"+td+"").find($(".issuesdocs-to-upload")).show();
          $(".td"+td+"").find($(".backdoc-to-upload")).hide(); 
        }

        $container.find($('#formcont')).html('');

        setRandomImg();

      } 
      else if(data.img !='delete') {
        $container.find($('.message')).html(msg); 
      }
    } else {
      $container.find($('.message')).html('Unknown error!'); 
    }
   } 

   function showResponseDocsDates(data, statusText, xhr, $form)  {
  
    console.log(data);
  
    var $container = $form.parents('.docs-dates');
    $container.find($('.message')).html('');

    $container.find($(".loading")).hide();
    if (statusText == 'success') {
      var msg = (data.error ? data.error.replace(/##/g, "<br />") : '');
  
          $container.find('.pdocs-button').css('display','none'); 

        $container.find($('#formcont')).html('');
    } else {
      $container.find($('.message')).html('Unknown error!'); 
    }
   } 


   function addMoreWorks(button){
 
    var $lastwork = $(button).parents('.works-container-basic .container').clone();
    var leftworks = $lastwork.length;

    //var h3 = +$lastwork.find('h3').text().match(/[0-9]/g)[0]; 

    //$lastwork.find('h3').text('Add finished work #'+(leftworks+1)+'');
    $lastwork.find('.result').html('');
    $lastwork.find('.fileToUpload').val('');
    $lastwork.find('textarea').val('');
    $lastwork.find('select option:eq(0):selected');

    var uploaded_list = $(".upload-list").length == 0 ? leftworks == 4 : (leftworks + $(".upload-list").length == 4); 

    if(leftworks == uploaded_list)  { $lastwork.find('.button-addmore').css('display','none');}

    $lastwork.insertAfter($('.works-container-basic .container').last()); 

    $('.works-container-basic .container:not(:last)').find('.button-addmore').css('display','none');
    $('.works-container-basic .container:last').find('.button-addmore-remove').css('display','block');

    //loadNewFinishedWorks();

    }

    function removeWork(element){
     //var $lastwork = $('.works-container-basic .container:last');

     //var leftworks = +$lastwork.find('h3').text().match(/[0-9]/g)[0];

     //$lastwork.find('h3').text('Your finished work #'+(leftworks-1)+'');

     $('.works-container-basic .container:last').find('.button-addmore').css('display','block');

     if($(element).find('input[name=work_id]').length > 0) {
      $(element).parents('.works-container-basic > .container').remove();
      finished_works--;
    }

    if(finished_works < 5) $('.to-upload').css('display', 'block');

    }

    var finished_works = +'<?=count($finished_works) ?>' > 0  ? +'<?=count($finished_works) ?>' : 0;

    var docsinsert = 0; /* done insert or update php */

    function loadNewFinishedWorks(){

     $("img.loading").hide();


      if(arguments){ 

      var $form = $(arguments[0]);
      var actions = arguments[1];

      switch(actions){
        case 'finished_works':
        var beforeFunc = showRequestWorks;
        var successFunc = showResponseWorks;
        break;

        case 'driver':
 
        case 'pers_docs':

        var beforeFunc = showRequestDocs;
        var successFunc = showResponseDocs;
        break;

        case 'pers_docs_dates':
        var beforeFunc = showRequestDocsDates;
        var successFunc = showResponseDocsDates;
        break; 

      }

      var options = {
        beforeSubmit:  beforeFunc,
        success:       successFunc,
        error:function(a, b, c){
          console.log(a, b, c);
        },
        url:       '<?=WEBSITE.TEMPLATE_FOLDER?>plugins/image-uploader/upload4jquery.php',  // your upload script
        dataType:  'json',
        data: {action:actions, docsinsert:docsinsert}
      };

      $form.find($('.message')).html('');
      $form.ajaxSubmit(options);

    }

    }
  
    function hide_all_docs_tr(){
       $("#tr-pass, #tr-driver-license").css('display','none');
       $(".navdocs li").removeClass('active');
    }

    function checkWorksSelected(work_type){
      
      var allSelectedWorks = [];       

      $('.one-'+work_type).find('select option:selected').each(function(){
          allSelectedWorks.push($(this).val());
      });
      return allSelectedWorks;
    }

    function addMoreOutside(button, confirmation){

      var work_type = $(button).hasClass('one-outside-button') ? 'outside' : 'inside';	

      var length_of_works = $('.one-'+work_type).length;

      var $orig_outside = $(button).parents('.one-'+work_type);

      var $outside_works = $orig_outside.clone();

      if(confirmation == 'confirmed' && $orig_outside.find('select').prop("disabled"))
      $outside_works.find('select').removeAttr("disabled");

      else{
      $outside_works.find('select').remove("disabled","disabled");	
      $orig_outside.find('select').attr("disabled","disabled");
      }
      //$outside_works.find('select').remove('disabled') : $outside_works.find('select').attr('disabled','disabled');
      
      if(confirmation == 'confirmed' && length_of_works <= 4) {

      var allSelectedWorks = checkWorksSelected(work_type);

      /* If outside or inside works loaded */

      if(typeof outside_list !== 'undefined' || typeof inside_list !== 'undefined'){
       $outside_works.find('select option').not(':first').remove();	

      $.each((work_type == 'outside' ? AllOutSideWorks : AllInsideWorks), function(index,value){
         $("<option value='"+value.id+"'>"+value.name+"</option>").appendTo($outside_works.find('select'));
      });
     }

      $.each(allSelectedWorks, function(index){
        $outside_works.find('select option[value='+allSelectedWorks[index]+']').remove();
      }); 

      $outside_works.insertAfter($('.one-'+work_type).last());

      }

      $('.one-'+work_type).find($('.one-'+work_type+'-button')).not(':last').css('opacity',0);

      if(length_of_works == 4) $('.one-'+work_type).find($('.one-'+work_type+'-button:last')).text('Add');

      if(length_of_works == 5) $('.one-'+work_type).find($('.one-'+work_type+'-button')).css('opacity',0);

    }

     function removeOneOutside(button){

      var work_type = $(button).hasClass('one-outside-remove-button') ? 'outside' : 'inside';
      var $work = $(button).parents('.one-'+work_type);	
      //var work_selected = $work.find('select option:selected').val(); 

      var length_of_works = $('.one-'+work_type).length;

      if(length_of_works == 1) {
      	$work.prev().css('display','block');
      	$(button).parents('.one-'+work_type).css('display','none');
      	//returnBackOutsideOption($work);
      }

      else {
      	//returnBackOutsideOption($work, work_selected);
      	$(button).parents('.one-'+work_type).remove();
      	$('.one-'+work_type+'-button:last').css('opacity','5'); 
      }

    }

    function resetOutsideWorks(work_type){

     $(".one-"+work_type).find('select option').not(':first').remove();
     $(".one-"+work_type).find('select').removeAttr('disabled');

     var AllWorks = work_type == 'outside' ? AllOutSideWorks : AllInsideWorks;
 
     $.each(AllWorks, function(index, value){
     $("<option value='"+value.id+"'>"+value.name+"</option>").appendTo($(".one-"+work_type).find('select'));
     });
    }

    function openMagnify(image, what /* fin works or docs */){

    var img = image.replace('/thumb/', '/photo/'); 

    $.magnificPopup.open({
    items: {
      src: img
    },
    type: 'image'
    });
    }

    function openMagnifyMessage(message){
    
    $(".popup-with-zoom-anim").trigger('click'); 
    $(".popup-message").text(message);

    }

    function ChangesFinWorksData(button){
       
       var $button_ok = $(button).next();
       var $content = $(button).parent();

       $button_ok.toggleClass('glyphicon glyphicon-ok');
   
       if($button_ok.hasClass('glyphicon-ok')){      
       $content.find('textarea').removeAttr('readonly');
       $content.find('select').removeAttr('disabled');
       }

       else {
       $content.find('textarea').attr('readonly',true);
       $content.find('select').attr('disabled','disabled'); 
       }
    }

    function UpdateFinWorksData(button){
       $(button).attr('class', '');

       var $content = $(button).parent();
       updateFinWork($content);   
    }

</script>

    <!--container end-->

   <?php $conn->close(); include('blocks/footer.php'); ?>

   <!-- Trigger/Open The Modal -->
<button id="myBtn" style="display:none">Open Modal</button>
<!-- The Modal -->
<div id="myModal" class="modal">
  <!-- Modal content --> 
  <div class="modal-content" align="center">
 <span class="close">&times;</span> 
  </div>
</div>

<img id="myImg" src="" alt="Snow" style="width:100%;max-width:300px" style="display:none;">

<!-- The Modal -->
<div id="imgModal" class="imgModal">
  <span class="img-close">&times;</span>
  <img class="img-modal-content" id="img01">
  <div id="caption"></div>
</div>

<a class="popup-with-zoom-anim" href="#small-dialog" >Open with fade-zoom animation</a><br/>
<div id="small-dialog" class="zoom-anim-dialog mfp-hide">
        <p class="popup-message"></p>
</div>

  </body>
</html>
