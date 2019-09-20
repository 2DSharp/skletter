namespace java com.skletter.DTO
namespace php Skletter.Model.RemoteService.DTO


struct AuthResult
{
  1: required bool success
  2: optional string error
}

enum Status
{
  TEMP,
  ACTIVE,
  DEACTIVATED,
  RECOVERY,
  SUSPENDED
}

struct UserDTO
{
  1: optional i32 id
  2: required string name
  3: required string email
  4: required string username
  5: optional Status status
  6: optional string password
  7: optional string ipAddr
}