var app = angular.module('app', []);
app.controller('managedashboard', function ($scope, $http, $sce,$timeout) {
    $scope.filter = {};
    $scope.fetchDashboardData = function(){
        
    }
    
    $scope.applyFilter = function(){
        console.log($scope.filter);
    }
});
