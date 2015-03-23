angular.module('insannu', ['ngResource'])
  .controller('SearchController', ['$scope', '$resource', function($scope, $resource) {
    var StudentsFactory = $resource('/api/search/:search');
    $scope.loaded = false;

    $scope.$watch('search', function() {
      $scope.listEmail = "";
      $scope.loaded = false;
      $scope.students = StudentsFactory.query({search:$scope.search}, function() {
        $scope.students.forEach(function(obj) {
          $scope.listEmail += obj.mail + ", ";
        });
        $scope.loaded = true;
        console.log('Found');
      });
    });

    $scope.setSearch = function(val) {
      $scope.search = val;
    };
  }])
;
