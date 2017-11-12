app.controller('managerider', function ($scope, $http, $sce,$timeout, riderId, riderList, resetPassword) {
    $scope.errorShow = false;
    $scope.riderId = riderId;
    $scope.resetPassword = false;
    if(resetPassword != undefined && resetPassword==1) {
        $scope.resetPassword = true;
    }
    $scope.riderData = {};
    $scope.riderData.location_id='';
    $scope.riderData.status=1;
    $scope.searchRiderParams = {};
    $scope.getRiders = function() {
        $http({
            method: 'POST',
            data : ObjecttoParams($scope.searchRiderParams),
            url: serverAdminApp + 'rider/riderList',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.riderList = {};
            if(response.status == 'success'){
                $scope.riderList = response.data;
                if($scope.searchRiderParams.id != undefined && $scope.searchRiderParams.id>0) {
                    $scope.riderData = $scope.riderList[$scope.searchRiderParams.id];
                    $scope.riderData.password = '';
                }
            }
        });
    }
    if(riderId >0 || riderList>0) {
        $scope.searchRiderParams.id = riderId;
        $scope.getRiders();
    } 
    
    $scope.saveRider = function (riderData) {
		var error = ' ';
                console.log($scope.resetPassword)
                if($scope.riderId==0 || ($scope.resetPassword && $scope.riderId>0)) {
                    if(riderData.location_id == undefined || riderData.location == ''){
                            error = 'Please Select Market Location.' ;
                    }                
                    if(riderData.confirm_password == undefined || riderData.confirm_password == '' || riderData.password !=riderData.confirm_password){
                            error = 'Confrim password does not match with confirm password.' ;
                    }                
                    if(riderData.password == undefined || riderData.password == ''){
                            error = 'Password can not be empty' ;
                    }
                }
		if(riderData.email == undefined || riderData.email == ''){
			error = 'Please enter Email Id.' ;
		}                
		if(riderData.name == undefined || riderData.name == ''){
			error = 'Please Enter Rider Name' ;
		}                
		if(error == ' '){       
			$http({
				method: 'POST',
				data : ObjecttoParams(riderData),
				url: serverAdminApp + 'rider/saverider',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			}).success(function (response) {
				if (response.status == 'success') {
                                        $scope.successShow = true;
                                        $scope.successMsg = response.msg ;
                                        $scope.successShow = false;
                                        var path = serverAdminApp + 'rider';
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