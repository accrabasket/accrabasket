app.controller('managelocation', function ($scope, $http, $sce,$timeout) {
    $scope.searchLocation = {};
    $scope.restrictedLocationList = {};
    $scope.getRestrictedLocation = function() {
        $http({
            method: 'POST',
            data : ObjecttoParams($scope.searchLocation),
            url: serverAdminApp + 'dashboard/restrictedlocationList',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.location = {};
            if(response.status == 'success'){
                $scope.restrictedLocationList = response.data;
            }
        });
    }
    $scope.getRestrictedLocation();
});	