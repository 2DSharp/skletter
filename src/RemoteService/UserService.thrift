namespace java com.skletter.service
namespace php Skletter.RemoteService.UserService

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
  1: required i32 id
  2: required string email
  3: required string username
  4: required Status status
}

exception UserExists
{
  1: string 	identifier
  2: i16	errorCode
}

exception NonExistentUser
{
  1: string	identifier
  2: i16	errorCode
 }

typedef i32 int

service UserWriteService
{
  void registerNew(1: string name, 2: string username, 3: string email) throws (1: UserExists exists),
  void updateStatus(1: int id, 2: Status status),
  void updateName(1: int id, 2: string name),
  void updateUsername(1: int id, 2: string newUsername) throws (1: UserExists exists),
  void updateProfilePicture(1: int id, 2: string newImage)
  void deleteProfilePicture(1: int id),
  void deactivate(1: int id),
  void deleteUser(1: int id)
}

service UserReadService
{
  User getUserByEmail(1: string email) throws (1: NonExistentUser nonexistentUser),
  User getUserByID(1: int id) throws (1: NonExistentUser nonexistentUser),
  User getUserByUsername(1: string username) throws (1: NonExistentUser nonexistentUser),
  Profile getProfile(1: string username) throws (1: NonExistentUser nonexistentUser)
  
}
