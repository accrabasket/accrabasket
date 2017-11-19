app.controller('managestore', function ($scope, $http, $sce,$timeout,storeList) {
    $scope.errorShow = false;
    $scope.storeData = {};
    $scope.storeData.status = 1;
    $scope.searchLocation = {};
    $scope.getLocation = function() {
        $http({
            method: 'POST',
            data : ObjecttoParams($scope.searchLocation),
            url: serverAdminApp + 'dashboard/locationList',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.location = {};
            if(response.status == 'success'){
                $scope.locationList = response.data;
                if($scope.searchLocation.id != undefined && $scope.searchLocation.id>0) {
                    $scope.storeData = $scope.locationList[$scope.searchLocation.id];
                }
            }
        });
    }
    var storeList = jQuery.parseJSON(storeList);
    if(storeList != undefined && storeList != null) {
        $scope.storeData.id = storeList['id'];
        $scope.storeData.location_id = storeList['location_id'];
        $scope.storeData.address = storeList['address'];
        $scope.storeData.store_name = storeList['store_name'];
    }    
    
    $scope.saveStore = function (storeData) {
		var error = ' ';
		if(storeData.location_id == undefined || storeData.location_id == ''){
			error = 'Please select location.' ;
		}
		if(storeData.address == undefined || storeData.address == ''){
			error = 'Please enter address.' ;
		}                
		if(storeData.store_name == undefined || storeData.store_name == ''){
			error = 'Please enter store name.' ;
		}       
                
                storeData.lat = $('#location_id option:selected').attr('lat');
                storeData.lng = $('#location_id option:selected').attr('lng');
                
		if(error == ' '){       
			$http({
				method: 'POST',
				data : ObjecttoParams(storeData),
				url: serverMerchantApp + 'savestore',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			}).success(function (response) {
				if (response.status == 'success') {
                                        $scope.successShow = true;
                                        $scope.successMsg = response.msg ;
                                        $scope.successShow = false;
                                        var path = serverMerchantApp + 'managestore';
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