var app = angular.module('notification', ['ui.bootstrap']);
app.controller('notificationController', function ($scope, $http) {
    $scope.filter = {};
    $scope.filter.type = 'admin';
    $scope.filter.all_notification = 1;
    $scope.filter.page = 1;
    $scope.getNotificationList = function() { 
        $http({
            method: 'POST',
            url: serverMerchantApp + 'dashboard/getNotification',
            data : ObjecttoParams($scope.filter),
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        }).success(function (response) {
            if(response.status == 'success'){
                $scope.notificationList = response.data;
                $scope.numberOfRecord = response.totalRecord;
            }else{
                $scope.numberOfRecord = 0;
            }
        });
    }
    
    $scope.selectPage = function(page_number) {
        $scope.filter.page = page_number;
        $scope.getNotificationList();
    };
    
    $scope.getNotificationList();
});	
