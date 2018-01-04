var app = angular.module('app', []);
app.controller('managebanner', function ($scope, $http, $sce,$timeout) {
    $scope.errorShow = false;
    
    $scope.bannerData = {};
    $scope.id = '';
    
//    if(taxList != ''){
//        var taxList = jQuery.parseJSON(taxList);
//        $scope.taxData.tax_name = taxList.tax_name;
//        $scope.taxData.tax_value = taxList.tax_value;
//        $scope.id = taxList.id;
//        console.log($scope.taxData);
//    }
    
    $scope.savebanner = function (bannerData) {
		var error = ' ';
                
		if($('#banner_img').val() != ''){
			$scope.bannerData.image = $('#banner_img').val();
                        $scope.bannerData.image_name = $('#image_name').val();
		}else{
                    error = 'Please select image' ;
                }  
                if(bannerData.link == undefined || bannerData.link == ''){
			error = 'Please enter redirect link' ;
		}
                console.log($scope.bannerData);
		if(error == ' '){       
			$http({
				method: 'POST',
				data : ObjecttoParams($scope.bannerData),
				url: serverAdminApp + 'dashboard/savebanner',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			}).success(function (response) {
				if (response.status == 'success') {
                                        $scope.successShow = true;
                                        $scope.successMsg = response.msg ;
                                        $scope.successShow = false;
                                        var path = serverAdminApp + 'dashboard/managetax';
                                        window.location.href = path;
				}else{
                                    $scope.errorShow = true;
                                    $scope.errorMsg = response.msg == undefined ? 'somthing went wrong ':response.msg;
                                    $timeout(function(){
                                            $scope.errorShow = false;
                                    },2000);
				}
			});
		}else{
			$scope.errorShow = true;
			$scope.errorMsg = error;
			$timeout(function(){
				$scope.errorShow = false;
			},2000)
		}
		
    };
	
	
});

 function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        $('#image_name').val(input.files[0]['name']);
        reader.onload = function (e) {
            $('#banner_img').val(e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function uploadImageInTemp(obj) {
    readURL(obj);
}