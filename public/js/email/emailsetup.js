var editData ;
var app = angular.module('emailSetup',[]);
app.controller('emailSetupController',function($scope,$timeout,$http,$sce){
    $scope.mailType = '';
    console.log(editData);
    $scope.emailDataArr = {};
    $scope.errorMsg = false;
    $scope.errCode = '';
    $scope.successMsg = false;
    $scope.hitApi = 0;
    $scope.typeArr = [{'id':'send_activation_link_to_admin','name':'Send Activation Link To Admin'},{'id':'manual','name':'Manual'},{'id':'7_day_reminder','name':'7 Day Reminder'},{'id':'5_day_reminder','name':'5 Day Reminder'},{'id':'3_day_reminder','name':'3 Day Reminder'},{'id':'final_call','name':'Final Call'}];
    $scope.changeMailtype = function(){
        $scope.subject = $scope.mailType;
    }
    
    $scope.emailSumbit = function(){
        var editorText = CKEDITOR.instances.articleContent.getData();
        if($scope.mailType == undefined){
            $scope.errCode = 'Please Select one type .';
            $scope.errorMsg = true;
        }else if($scope.nickName == undefined){
            $scope.errCode = 'Please provide nick name .';
            $scope.errorMsg = true;
        }else if($scope.subject == undefined){
            $scope.errCode = 'Please provide subject .';
            $scope.errorMsg = true;
        }else if($scope.ccMail != undefined){
            var emailFlag = 0;
            var email = $scope.ccMail.split(',');
            for(var i=0;i<email.length;i++){
                var atpos = email[i].indexOf("@");
                var dotpos = email[i].lastIndexOf(".");
                if (atpos<1 || dotpos<atpos+2 || dotpos+2>=email[i].length) {
                    emailFlag++ ;
                } 
            }
            if(emailFlag > 0){
            $scope.errCode = 'Please provide valid email id.';
            $scope.errorMsg = true;
            }
            
        }else{
            $scope.errorMsg = false;
        }
        $scope.hitApi = $scope.errorMsg;
        $timeout(function(){
            $scope.errorMsg = false;
        },3000);
        
        $scope.emailDataArr.type = $scope.mailType;
        $scope.emailDataArr.nick_name = $scope.nickName;
        $scope.emailDataArr.subject = $scope.subject;
        $scope.emailDataArr.cc_mail = $scope.ccMail;
        $scope.emailDataArr.bcc_mail = $scope.bccMail;
        $scope.emailDataArr.body_msg = editorText;
        if(!$scope.hitApi){
            $http({
                method:'POST',
                url : serverUrl+'dashboard/saveemaildata',
                data : jQuery.param($scope.emailDataArr),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            }).success(function(response){
                if(response.status){
                    $scope.successMsg = true;
                    $scope.succe = response.msg;
                }else{
                    $scope.errorMsg = true;
                    $scope.errCode = response.msg;
                }  
            });
        }
        
    }
});

app.controller('emailSetupListController',function($scope,$http,$window){
    $scope.templateList = {};
    $scope.getEmailTemplateList = function(){
        $http({
                method:'POST',
                url : serverUrl+'dashboard/gettemplatelist',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            }).success(function(response){
                if(response.status){
                    $scope.successMsg = true;
                    $scope.templateList = response.data;
                }else{
                    $scope.errorMsg = true;
                    $scope.errCode = response.msg;
                }  
            });  
        }
        
    $scope.deleteEmailTemplate = function(template_id){
        $scope.emailTemplateid = {'id':template_id};
        $http({
                method:'POST',
                url : serverUrl+'dashboard/deleteEmailTemplate',
                data : jQuery.param($scope.emailTemplateid),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            }).success(function(response){
                if(response.status){
                    $scope.successMsg = true;
                    $scope.getEmailTemplateList();
                }else{
                    $scope.errorMsg = true;
                    $scope.errCode = response.msg;
                }  
            });
    } 
    
    $scope.editEmailTemplate = function(template_id){
        $scope.emailTemplateid = {'id':template_id};
        $http({
                method:'POST',
                url : serverUrl+'dashboard/emailsetup?'+template_id,
                data : jQuery.param($scope.emailTemplateid),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            }).success(function(response){
                $('#mainlist').html(response);
            });
    }    

});

