namespace java com.skletter
namespace php Skletter.Model.RemoteService.UserService

include "DTO.thrift"
include "Exception.thrift"
typedef i32 int

service UserService
{
    int registerNew(1: DTO.UserDTO user) throws (1: Exception.UserExists exists,
                                                 2: Exception.ValidationError error,
                                                 3: Exception.NullDTOException nullDTOException),
    void confirmAccountViaToken(1: int id, 2: string token),
    void confirmAccountViaPIN(1: int id, 2: i16 pin),
    void updateName(1: int id, 2: string name),
    void updateUsername(1: int id, 2: string newUsername) throws (1: Exception.UserExists exists),
    void updateProfilePicture(1: int id, 2: string newImage)
    void deleteProfilePicture(1: int id),
    void deactivate(1: int id),
    void deleteUser(1: int id)

    DTO.UserDTO getUserByEmail(1: string email) throws (1: Exception.NonExistentUser nonexistentUser),
    DTO.UserDTO getUserByID(1: int id) throws (1: Exception.NonExistentUser nonexistentUser),
    DTO.UserDTO getUserByUsername(1: string username) throws (1: Exception.NonExistentUser nonexistentUser),
    DTO.UserDTO getUserByIdentifier(1: string username) throws (1: Exception.NonExistentUser nonexistentUser),

    DTO.UserDTO loginWithPassword(1: string identifier, 2: string password) throws (1: Exception.NonExistentUser nonexistentUser,
                                                                                2: Exception.PasswordMismatch mismatch),
    DTO.AuthResult authenticateCookie(1: string cookie, 2: string userAgent) throws (1: Exception.NonExistentUser nonexistentUser),
    DTO.AuthResult authenticateNonceToken(1: int userId, 2: string token) throws (1: Exception.NonExistentUser nonexistentUser),
    DTO.AuthResult authenticateNoncePin(1: int userId, 2: i16 pin) throws (1: Exception.NonExistentUser nonexistentUser),
}