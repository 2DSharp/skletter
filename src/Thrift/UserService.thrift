namespace java com.skletter.Romeo
namespace php Skletter.Model.RemoteService.UserService

include "DTO.thrift"
include "Exception.thrift"
typedef i32 int

service UserService
{
    int registerNew(1: DTO.UserDTO user) throws (1: Exception.UserExists exists,
                                                 2: Exception.ValidationError error,
                                                 3: Exception.NullDTOException nullDTOException),

    DTO.UserDTO loginWithPassword(1: string identifier, 2: string password, 3: DTO.LoginMetadata metaData) throws (1: Exception.NonExistentUser nonexistentUser,
                                                                             2: Exception.PasswordMismatch mismatch),

}