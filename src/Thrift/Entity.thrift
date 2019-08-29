namespace java com.skletter.Model.Entity
namespace php Skletter.Model.RemoteService.Entity

enum Status
{
  TEMP,
  ACTIVE,
  DEACTIVATED,
  RECOVERY,
  SUSPENDED
}

struct Profile
{
  1: required string username
  2: required string name
  3: optional string picture
}

struct User
{
  1: optional i32 id
  2: required string email
  3: required string username
  4: optional Status status
}