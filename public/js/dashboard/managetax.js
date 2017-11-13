var app = angular.module('app', []);
app.controller('managetax', function ($scope, $http, $sce,$timeout, taxList) {
    $scope.errorShow = false;
    
    $scope.taxData = {};
    $scope.id = '';
    
    if(taxList != ''){
        var taxList = jQuery.parseJSON(taxList);
        $scope.taxData.tax_name = taxList.tax_name;
        $scope.taxData.tax_value = taxList.tax_value;
        $scope.id = taxList.id;
        console.log($scope.taxData);
    }
    
    $scope.savetax = function (taxData) {
		var error = ' ';
		if(taxData.tax_name == undefined || taxData.tax_name == ''){
			error = 'Please enter tax name' ;
		}
                if(taxData.tax_value == undefined || taxData.tax_value == ''){
			error = 'Please enter tax value' ;
		}
		if($scope.id != ''){
			$scope.taxData.id = $scope.id;
		}      
                
		if(error == ' '){       
			$http({
				method: 'POST',
				data : ObjecttoParams(taxData),
				url: serverAdminApp + 'dashboard/savetax',
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