var server_url = 'http://localhost/hireapplicants/';
var app = angular.module('newCompanyList',[]);
app.controller('companyListController', function($scope, $http){
    $scope.companyFilter = {};
    $scope.companyActivationParams = {};
    $scope.companyFilter.pageNo = 1;
    $scope.companylist = function(){        
        $scope.companyFilter.status = 0;
        $http({
            url: server_url+"admin/dashboard/companylist",
            method: "POST",
            data:ObjecttoParams($scope.companyFilter),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            $scope.companyFilter = {};
            if(response.status==true){
                $scope.company_list = response.data;
            }else{
                //to do
            }
        });
    }
    $scope.companylist();
    $scope.approveCompany = function(company_id, status){
        $scope.companyActivationParams.company_id = company_id;
        $scope.companyActivationParams.status = status;
        $http({
            url: server_url+"admin/dashboard/activateordeactivatecompany",
            method: "POST",
            data:ObjecttoParams($scope.companyActivationParams),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).success(function(response) {
            $scope.companyActivationParams = {};
            if(response.status==true){
                $scope.companylist();
            }else{
                //to do
            }
        });        
    };    
});