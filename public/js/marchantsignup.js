function ObjecttoParams(obj) {
    var p = [];
    for (var key in obj) {
        p.push(key + '=' + encodeURIComponent(obj[key]));
    }
    return p.join('&');
};
var app = angular.module('marchantapp', []);
app.controller('marchantController', function ($scope, $http, $sce,$timeout) {
    $scope.errorShow = false;
    
   
    $scope.register = function () {
		var error = ' ';
		if($scope.name == undefined || $scope.name == ''){
			error = 'Please enter owner name' ;
		}
		
		if($scope.phone_number == undefined || $scope.phone_number == ''){
			error = 'Please select phone number' ;
		}
                
                if($scope.email_id == undefined || $scope.email_id == ''){
			error = 'Please enter valid email' ;
		}
                
                if($scope.password == undefined || $scope.password == ''){
			error = 'Please enter price ' ;
		}
                
                if($scope.confirm_password == undefined || $scope.confirm_password == ''){
			error = 'Please enter confirm password ' ;
		}
                
                if($scope.address == undefined || $scope.address == ''){
			error = 'Please enter address ' ;
		}
                
                if($scope.confirm_password != $scope.password){
			error = 'Password and confirm password should be same ' ;
		}
                
                
                
		if(error == ' '){
			var dataList = {};
			dataList.name = $scope.name;
			dataList.ic_number = $scope.ic_number;
                        dataList.phone_number = $scope.phone_number;
                        dataList.email_id = $scope.email_id;
			dataList.password = $scope.password;
                        dataList.address = $scope.address;
                        dataList.bank_name = $scope.bank_name;
                        dataList.bank_account_number = $scope.bank_account_number;
                        dataList.confirm_password = $scope.confirm_password;
                        
			$http({
				method: 'POST',
				data : ObjecttoParams(dataList),
				url: serverAppUrl + '/addmerchant',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			}).success(function (response) {
				if (response.status) {
					$scope.successShow = true;
			                $scope.successMsg = response.msg;
				}else{
					$scope.errorShow = true;
					$scope.errorMsg = response.msg == undefined ? 'somthing went wrong ':response.msg;
				}
				$timeout(function(){
					$scope.successShow = false;
					$scope.errorShow = false;
				},2000)
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