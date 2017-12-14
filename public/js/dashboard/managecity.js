app.controller('managecity', function ($scope, $http, $sce, $timeout,cityList) {
    $scope.errorShow = false;

    $scope.cityData = {};
    $scope.cityData.country_id = 0;
    $scope.cityData.active = 1;
    $scope.searchLocation = {};

    getCountry();
    function getCountry() {
        $http({
            method: 'POST',
            url: serverAdminApp + 'dashboard/countryList',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            $scope.countrylist = {};
            if (response.status == 'success') {
                $scope.countrylist = response.data;

            }
        });
    }

console.log(cityList);
    if(cityList != undefined && cityList != '') {
        $scope.cityData.id = cityList.id;
        $scope.country_id = cityList.country_id;
        $scope.cityData = cityList;
        console.log(cityList);
    }    

    $scope.saveCity = function (cityData) {
        var error = ' ';
        if (cityData.country_id == undefined || cityData.country_id == '') {
            error = 'Please select country.';
        }
        if (cityData.city_name == undefined || cityData.city_name == '') {
            error = 'Please enter city.';
        }
            

        if (error == ' ') {
            $http({
                method: 'POST',
                data: ObjecttoParams(cityData),
                url: serverAdminApp + 'dashboard/savecity',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            }).success(function (response) {
                if (response.status == 'success') {
                    $scope.successShow = true;
                    $scope.successMsg = response.msg;
                    $scope.successShow = false;
                    var path = serverAdminApp + 'dashboard/managecity';
                    window.location.href = path;
                } else {
                    $scope.errorShow = true;
                    $scope.errorMsg = response.msg == undefined ? 'somthing went wrong ' : response.msg;
                    $timeout(function () {
                        $scope.errorShow = false;
                    }, 2000);
                }
            });
        } else {
            $scope.errorShow = true;
            $scope.errorMsg = error;
            $timeout(function () {
                $scope.errorShow = false;
            }, 2000)
        }

    };


});	