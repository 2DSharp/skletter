namespace java com.skletter.Service
namespace php Skletter.Model.RemoteService.UserService

include "Entity.thrift"
include "Exception.thrift"
typedef i32 int

service UserService
{
    void registerNew(1: Entity.User user, 2: Entity.Profile profile) throws (1: Exception.UserExists exists, 
                                                                             2: Exception.ValidationError error),
    void updateStatus(1: int id, 2: Entity.Status status),
    void updateName(1: int id, 2: string name),
    void updateUsername(1: int id, 2: string newUsername) throws (1: Exception.UserExists exists),
    void updateProfilePicture(1: int id, 2: string newImage)
    void deleteProfilePicture(1: int id),
    void deactivate(1: int id),
    void deleteUser(1: int id)

    Entity.User getUserByEmail(1: string email) throws (1: Exception.NonExistentUser nonexistentUser),
    Entity.User getUserByID(1: int id) throws (1: Exception.NonExistentUser nonexistentUser),
    Entity.User getUserByUsername(1: string username) throws (1: Exception.NonExistentUser nonexistentUser),
    Entity.User getUserByIdentifier(1: string username) throws (1: Exception.NonExistentUser nonexistentUser),
    Entity.Profile getProfile(1: string username) throws (1: Exception.NonExistentUser nonexistentUser)
}