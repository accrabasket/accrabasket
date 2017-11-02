var app = angular.module('service', []);
app.controller('packagelistController', function ($scope, $http, $sce) {
    $scope.getallservice = function () {
        $http({
            method: 'POST',
            url: serverUrl + 'common/servicelist',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            if (response.status == true) {
                $scope.servicelist = response.data;
            }
        });
    };
    $scope.getallservice();
    $scope.addToCart = function (service_id, service_detail_id) {
        var serviceToAddIntoCart = {};
        serviceToAddIntoCart.service_id = service_id;
        serviceToAddIntoCart.service_detail_id = service_detail_id;
        $http({
            method: 'POST',
            data: ObjecttoParams(serviceToAddIntoCart),
            url: serverUrl + 'common/addtocart',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            if (response.status == true) {
                $scope.servicelist = response.data;
            }
        });
    }
});

