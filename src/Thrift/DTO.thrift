namespace java com.skletter.Romeo.DTO
namespace php Skletter.Model.RemoteService.DTO

enum ErrorCode
{
      PASSWORD_TOO_SHORT
      PASSWORD_EMPTY
      PASSWORD_TOO_WEAK
      PASSWORD_EQUALS_CREDENTIALS
      PASSWORD_WRONG
      USER_UNAVAILABLE
      USERNAME_EXISTS
      EMAIL_EXISTS
      NAME_SIZE
      NAME_PATTERN
      EMAIL_INVALID
      USERNAME_SIZE
      USERNAME_PATTERN
      NAME_EMPTY
      USERNAME_EMPTY
      EMAIL_EMPTY
      IDENTIFIER_NONEXISTENT
}
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

enum Status
{
  TEMP,
  ACTIVE,
  DEACTIVATED,
  RECOVERY,
  SUSPENDED
}

struct CookieDTO
{
    1: required i32 id
    2: required string token
    3: required string expiry
    4: optional UserDTO user
    5: optional Notification notification
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
  8: optional Notification notification
}

struct LoginMetadata
{
    1: required string ipAddr
    2: required string headers,
    3: required string localSessionId,
}