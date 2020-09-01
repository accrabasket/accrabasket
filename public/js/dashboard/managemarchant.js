app.controller('marchantController', function ($scope, $http, $sce,$timeout, marchantList) {
    console.log(marchantList);
    $scope.errorShow = false;
    $scope.marchantData = {};
    if(marchantList != ''){
        var marchantList = jQuery.parseJSON(marchantList);
        $scope.marchantData = marchantList;
        $scope.marchantData.name = marchantList.first_name;
    }
    $scope.savemerchant = function () {
		var error = ' ';	                
                if($scope.marchantData.bank_name == undefined || $scope.marchantData.bank_name == ''){
			error = 'Bank name can not be empty. ' ;
		}
                if($scope.marchantData.address == undefined || $scope.marchantData.address == ''){
			error = 'Please enter address ' ;
		}
                if($scope.marchantData.email == undefined || $scope.marchantData.email == ''){
			error = 'Please enter valid email' ;
		}
		if($scope.marchantData.phone_number == undefined || $scope.marchantData.phone_number == ''){
			error = 'Please enter phone number' ;
		}
		if($scope.marchantData.ic_number == undefined || $scope.marchantData.ic_number == ''){
			error = 'Please enter ic number' ;
		}                
		if($scope.marchantData.name == undefined || $scope.marchantData.name == ''){
			error = 'Please enter owner name' ;
		}                
                
                
		if(error == ' '){
			$http({
				method: 'POST',
				data : ObjecttoParams($scope.marchantData),
				url: serverAdminApp + 'dashboard/saveMerchant',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			}).success(function (response) {
				if (response.status=='success') {
                                    var path = serverAdminApp + 'dashboard/managemerchant';
                                    window.location.href = path;
				}else{
					$scope.errorShow = true;
					$scope.errorMsg = response.msg == undefined ? 'somthing went wrong ':response.msg;
                                        $timeout(function(){
                                                $scope.errorShow = false;
                                        },2000)
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
        reader.onload = function (e) {
            $('#merchant_img').val(e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function uploadImageInTemp(obj) {
    readURL(obj);
}
