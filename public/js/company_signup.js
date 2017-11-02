var server_url = 'http://localhost/hireapplicants/';
var mainApp = angular.module("company_singup", []);
mainApp.controller('signup', function($scope, $http){
    $scope.country_id = 0;
    $scope.state_id = 0;
    $scope.getCountryList = function(){
        $http({
            url: server_url+"application/index/countrylist",
            method: "POST"
        }).success(function(response) {
        }).error(function() {
            $scope.status = status;
        });        
    };
    $scope.getStateList = function(){
        $scope.state_params = {};
        $scope.state_params.country_id = $scope.country_id;
        $http({
            url: server_url+"application/index/statelist",
            method: "POST",
            data:ObjecttoParams($scope.state_params),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            $scope.state_list = {};
            if(response.status==true){
                $scope.state_list = response.data;
            }else{
                //to do
            }
        }).error(function() {
            $scope.status = status;
        });
    };
});