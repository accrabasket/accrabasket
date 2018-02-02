app.controller('managelocation', function ($scope, $http, $sce,$timeout, locationId, restrictedLocationId) {
    $scope.errorShow = false;
    
    $scope.locationData = {};
    $scope.locationData.country_id=0;
    $scope.locationData.active=1;
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
                console.log($scope.locationList);
                if($scope.searchLocation.id != undefined && $scope.searchLocation.id>0) {
                    $scope.locationData = $scope.locationList[$scope.searchLocation.id];
                    $scope.getCity($scope.locationData.country_id);
                }
            }
        });
    }
    $scope.getRestrictedLocation = function() {
        $http({
            method: 'POST',
            data : ObjecttoParams($scope.searchLocation),
            url: serverAdminApp + 'dashboard/restrictedlocationList',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.location = {};
            if(response.status == 'success'){
                $scope.locationList = response.data;
                console.log($scope.locationList);
                if($scope.searchLocation.id != undefined && $scope.searchLocation.id>0) {
                    $scope.locationData = $scope.locationList[$scope.searchLocation.id];
                    $scope.getCity($scope.locationData.country_id);
                }
            }
        });
    }    
    getCountry();
    function getCountry() {
        $http({
            method: 'POST',
            url: serverAdminApp + 'dashboard/countryList',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.countrylist = {};
            if(response.status == 'success'){
                $scope.countrylist = response.data;
            }
        });
    }
    
    $scope.getCity = function(country_id) {
        var data = {};
        data.country_id = country_id;
        $http({
            method: 'POST',
            data : ObjecttoParams(data),
            url: serverAdminApp + 'dashboard/cityList',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.citylist = {};
            if(response.status == 'success'){
                $scope.citylist = response.data;
            }
        });
    }
    
    if(locationId != undefined && locationId >0) {
        $scope.searchLocation.id = locationId;
        $scope.getLocation();
    }    
    
    $scope.saveLocation = function (locationData) {
		var error = ' ';
		if(locationData.country_id == undefined || locationData.country_id == ''){
			error = 'Please select country.' ;
		}
		if(locationData.address == undefined || locationData.address == ''){
			error = 'Please enter address.' ;
		}                
		if(locationData.googlelocation == undefined || locationData.googlelocation == ''){
			error = 'Please choose location from google.' ;
		}  
                if(locationData.city_id == undefined || locationData.city_id == ''){
			error = 'Please choose location from google.' ;
		}  
		if(error == ' '){       
			$http({
				method: 'POST',
				data : ObjecttoParams(locationData),
				url: serverAdminApp + 'dashboard/savelocation',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			}).success(function (response) {
				if (response.status == 'success') {
                                        $scope.successShow = true;
                                        $scope.successMsg = response.msg ;
                                        $scope.successShow = false;
                                        var path = serverAdminApp + 'dashboard/location';
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
		
    $scope.saveRestrictedLocation = function (locationData) {
		var error = ' ';
		if(locationData.country_id == undefined || locationData.country_id == ''){
			error = 'Please select country.' ;
		}
		if(locationData.address == undefined || locationData.address == ''){
			error = 'Please enter address.' ;
		}  
                if(locationData.city_id == undefined || locationData.city_id == ''){
			error = 'Please choose location from google.' ;
		}  
		if(error == ' '){       
			$http({
				method: 'POST',
				data : ObjecttoParams(locationData),
				url: serverAdminApp + 'dashboard/saverestrictedlocation',
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			}).success(function (response) {
				if (response.status == 'success') {
                                        $scope.successShow = true;
                                        $scope.successMsg = response.msg ;
                                        $scope.successShow = false;
                                        var path = serverAdminApp + 'dashboard/restrictedlocation';
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