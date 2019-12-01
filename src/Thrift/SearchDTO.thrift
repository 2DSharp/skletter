namespace java com.skletter.Search.DTO
namespace php Skletter.Model.RemoteService.Search.DTO

struct Error
{
    1: required string message
}
typedef string field

struct Notification
{
  1: required bool hasError = false
  2: optional map<field, Error> errors
}

struct SearchProfile
{
    1: required i32 indexId
    2: required string name
    3: required string username
    4: required string picture
}