📦app
 ┣ 📂Console
 ┃ ┣ 📂Commands
 ┃ ┃ ┣ 📜AuditAssetsCommand.php
 ┃ ┃ ┣ 📜CleanDeprecatedReportFields.php
 ┃ ┃ ┣ 📜CleanupAssetsCommand.php
 ┃ ┃ ┣ 📜FixDashboardAndReportSeeder.php
 ┃ ┃ ┣ 📜FixMissingControllers.php
 ┃ ┃ ┣ 📜FixServiceStructure.php
 ┃ ┃ ┣ 📜FlattenMigrations.php
 ┃ ┃ ┣ 📜MigrateOrdered.php
 ┃ ┃ ┣ 📜OrganizeAssetsCommand.php
 ┃ ┃ ┣ 📜ProjectStatusCommand.php
 ┃ ┃ ┣ 📜RefactorControllerStructure.php
 ┃ ┃ ┣ 📜RefactorDatabaseStructure.php
 ┃ ┃ ┣ 📜RefactorServiceStructure.php
 ┃ ┃ ┣ 📜RegenerateViewsStructure.php
 ┃ ┃ ┣ 📜RenameMigrations.php
 ┃ ┃ ┣ 📜RestructureAdminControllers.php
 ┃ ┃ ┗ 📜RestructureAdminServices.php
 ┃ ┗ 📂Traits
 ┃ ┃ ┗ 📜AuditsAssets.php
 ┣ 📂Helpers
 ┃ ┣ 📜CacheHelper.php
 ┃ ┣ 📜helpers.php
 ┃ ┣ 📜PaginationHelper.php
 ┃ ┣ 📜settings.php
 ┃ ┗ 📜UnitTypeIconHelper.php
 ┣ 📂Http
 ┃ ┣ 📂Controllers
 ┃ ┃ ┣ 📂Admin
 ┃ ┃ ┃ ┣ 📂Auth
 ┃ ┃ ┃ ┃ ┣ 📜ForgotPasswordController.php
 ┃ ┃ ┃ ┃ ┣ 📜LoginController.php
 ┃ ┃ ┃ ┃ ┗ 📜ResetPasswordController.php
 ┃ ┃ ┃ ┣ 📂Conversation
 ┃ ┃ ┃ ┃ ┣ 📜ConversationController.php
 ┃ ┃ ┃ ┃ ┣ 📜ConversationParticipantController.php
 ┃ ┃ ┃ ┃ ┣ 📜MessageAttachmentController.php
 ┃ ┃ ┃ ┃ ┣ 📜MessageController.php
 ┃ ┃ ┃ ┃ ┗ 📜MessageReadController.php
 ┃ ┃ ┃ ┣ 📂Dashboard
 ┃ ┃ ┃ ┃ ┗ 📜DashboardController.php
 ┃ ┃ ┃ ┣ 📂Employee
 ┃ ┃ ┃ ┃ ┗ 📜EmployeeController.php
 ┃ ┃ ┃ ┣ 📂Moderation
 ┃ ┃ ┃ ┃ ┗ 📜ModerationLogController.php
 ┃ ┃ ┃ ┣ 📂Notification
 ┃ ┃ ┃ ┃ ┗ 📜NotificationController.php
 ┃ ┃ ┃ ┣ 📂Profile
 ┃ ┃ ┃ ┃ ┗ 📜ProfileController.php
 ┃ ┃ ┃ ┣ 📂QRCode
 ┃ ┃ ┃ ┃ ┗ 📜QrCodeController.php
 ┃ ┃ ┃ ┣ 📂Rating
 ┃ ┃ ┃ ┃ ┣ 📜RatingCategoryController.php
 ┃ ┃ ┃ ┃ ┗ 📜RatingController.php
 ┃ ┃ ┃ ┣ 📂Report
 ┃ ┃ ┃ ┃ ┣ 📜ExportController.php
 ┃ ┃ ┃ ┃ ┣ 📜ReportCategoryController.php
 ┃ ┃ ┃ ┃ ┗ 📜ReportController.php
 ┃ ┃ ┃ ┣ 📂Setting
 ┃ ┃ ┃ ┃ ┗ 📜SettingController.php
 ┃ ┃ ┃ ┣ 📂Unit
 ┃ ┃ ┃ ┃ ┣ 📜FacilityController.php
 ┃ ┃ ┃ ┃ ┣ 📜UnitController.php
 ┃ ┃ ┃ ┃ ┣ 📜UnitDepartmentController.php
 ┃ ┃ ┃ ┃ ┣ 📜UnitPhotoController.php
 ┃ ┃ ┃ ┃ ┗ 📜UnitTypeController.php
 ┃ ┃ ┃ ┗ 📂User
 ┃ ┃ ┃ ┃ ┗ 📜UserController.php
 ┃ ┃ ┣ 📂Employee
 ┃ ┃ ┃ ┣ 📂Assignment
 ┃ ┃ ┃ ┣ 📂Auth
 ┃ ┃ ┃ ┃ ┣ 📜ForgotPasswordController.php
 ┃ ┃ ┃ ┃ ┣ 📜LoginController.php
 ┃ ┃ ┃ ┃ ┗ 📜ResetPasswordController.php
 ┃ ┃ ┃ ┣ 📂Conversation
 ┃ ┃ ┃ ┃ ┣ 📜ConversationController.php
 ┃ ┃ ┃ ┃ ┣ 📜MessageAttachmentController.php
 ┃ ┃ ┃ ┃ ┣ 📜MessageController.php
 ┃ ┃ ┃ ┃ ┗ 📜MessageReadController.php
 ┃ ┃ ┃ ┣ 📂Dashboard
 ┃ ┃ ┃ ┃ ┗ 📜DashboardController.php
 ┃ ┃ ┃ ┣ 📂Profile
 ┃ ┃ ┃ ┃ ┗ 📜ProfileController.php
 ┃ ┃ ┃ ┣ 📂Rating
 ┃ ┃ ┃ ┃ ┗ 📜RatingController.php
 ┃ ┃ ┃ ┣ 📂Report
 ┃ ┃ ┃ ┃ ┗ 📜ReportController.php
 ┃ ┃ ┃ ┗ 📂Unit
 ┃ ┃ ┃ ┃ ┗ 📜UnitController.php
 ┃ ┃ ┣ 📂Student
 ┃ ┃ ┃ ┣ 📂Auth
 ┃ ┃ ┃ ┃ ┣ 📜ForgotPasswordController.php
 ┃ ┃ ┃ ┃ ┣ 📜LoginController.php
 ┃ ┃ ┃ ┃ ┣ 📜RegisterController.php
 ┃ ┃ ┃ ┃ ┗ 📜ResetPasswordController.php
 ┃ ┃ ┃ ┣ 📂Conversation
 ┃ ┃ ┃ ┃ ┣ 📜ConversationController.php
 ┃ ┃ ┃ ┃ ┣ 📜MessageAttachmentController.php
 ┃ ┃ ┃ ┃ ┣ 📜MessageController.php
 ┃ ┃ ┃ ┃ ┗ 📜MessageReadController.php
 ┃ ┃ ┃ ┣ 📂Dashboard
 ┃ ┃ ┃ ┃ ┗ 📜DashboardController.php
 ┃ ┃ ┃ ┣ 📂Notification
 ┃ ┃ ┃ ┃ ┗ 📜NotificationController.php
 ┃ ┃ ┃ ┣ 📂Profile
 ┃ ┃ ┃ ┃ ┗ 📜ProfileController.php
 ┃ ┃ ┃ ┣ 📂QRCode
 ┃ ┃ ┃ ┃ ┗ 📜QrCodeController.php
 ┃ ┃ ┃ ┣ 📂Rating
 ┃ ┃ ┃ ┃ ┗ 📜RatingController.php
 ┃ ┃ ┃ ┣ 📂Report
 ┃ ┃ ┃ ┃ ┗ 📜ReportController.php
 ┃ ┃ ┃ ┗ 📂Unit
 ┃ ┃ ┃ ┃ ┗ 📜UnitController.php
 ┃ ┃ ┣ 📜Controller.php
 ┃ ┃ ┗ 📜LandingPageController.php
 ┃ ┣ 📂Middleware
 ┃ ┃ ┣ 📜AdminMiddleware.php
 ┃ ┃ ┣ 📜CheckRole.php
 ┃ ┃ ┣ 📜EmployeeMiddleware.php
 ┃ ┃ ┣ 📜GuestMiddleware.php
 ┃ ┃ ┗ 📜StudentMiddleware.php
 ┃ ┗ 📂Requests
 ┃ ┃ ┣ 📂Admin
 ┃ ┃ ┃ ┣ 📂Conversation
 ┃ ┃ ┃ ┃ ┣ 📂Attachment
 ┃ ┃ ┃ ┃ ┃ ┣ 📜DeleteMessageAttachmentRequest.php
 ┃ ┃ ┃ ┃ ┃ ┣ 📜ReplaceMessageAttachmentRequest.php
 ┃ ┃ ┃ ┃ ┃ ┗ 📜UploadMessageAttachmentRequest.php
 ┃ ┃ ┃ ┃ ┣ 📂Conversation
 ┃ ┃ ┃ ┃ ┃ ┣ 📜ConversationFilterRequest.php
 ┃ ┃ ┃ ┃ ┃ ┣ 📜StoreConversationRequest.php
 ┃ ┃ ┃ ┃ ┃ ┗ 📜UpdateConversationRequest.php
 ┃ ┃ ┃ ┃ ┣ 📂Message
 ┃ ┃ ┃ ┃ ┃ ┣ 📜LatestMessageRequest.php
 ┃ ┃ ┃ ┃ ┃ ┣ 📜MessageFilterRequest.php
 ┃ ┃ ┃ ┃ ┃ ┣ 📜SearchMessageRequest.php
 ┃ ┃ ┃ ┃ ┃ ┣ 📜StoreMessageRequest.php
 ┃ ┃ ┃ ┃ ┃ ┗ 📜UpdateMessageRequest.php
 ┃ ┃ ┃ ┃ ┣ 📂Participant
 ┃ ┃ ┃ ┃ ┃ ┣ 📜AddParticipantRequest.php
 ┃ ┃ ┃ ┃ ┃ ┣ 📜RemoveParticipantRequest.php
 ┃ ┃ ┃ ┃ ┃ ┗ 📜UpdateParticipantRequest.php
 ┃ ┃ ┃ ┃ ┗ 📂Read
 ┃ ┃ ┃ ┃ ┃ ┣ 📜MarkAllMessageReadRequest.php
 ┃ ┃ ┃ ┃ ┃ ┗ 📜MarkMessageReadRequest.php
 ┃ ┃ ┃ ┣ 📂Employee
 ┃ ┃ ┃ ┃ ┣ 📜AssignToUnitRequest.php
 ┃ ┃ ┃ ┃ ┣ 📜StoreEmployeeRequest.php
 ┃ ┃ ┃ ┃ ┗ 📜UpdateEmployeeRequest.php
 ┃ ┃ ┃ ┣ 📂Export
 ┃ ┃ ┃ ┃ ┗ 📜ExportRequest.php
 ┃ ┃ ┃ ┣ 📂Facility
 ┃ ┃ ┃ ┃ ┣ 📜StoreFacilityRequest.php
 ┃ ┃ ┃ ┃ ┗ 📜UpdateFacilityRequest.php
 ┃ ┃ ┃ ┣ 📂Message
 ┃ ┃ ┃ ┃ ┣ 📜MessageFilterRequest.php
 ┃ ┃ ┃ ┃ ┣ 📜StoreMessageRequest.php
 ┃ ┃ ┃ ┃ ┗ 📜UpdateMessageRequest.php
 ┃ ┃ ┃ ┣ 📂ModerationLog
 ┃ ┃ ┃ ┃ ┣ 📜ExportLogsRequest.php
 ┃ ┃ ┃ ┃ ┗ 📜FilterModerationLogRequest.php
 ┃ ┃ ┃ ┣ 📂Profile
 ┃ ┃ ┃ ┃ ┣ 📜UpdatePasswordRequest.php
 ┃ ┃ ┃ ┃ ┣ 📜UpdatePreferencesRequest.php
 ┃ ┃ ┃ ┃ ┗ 📜UpdateProfileRequest.php
 ┃ ┃ ┃ ┣ 📂QrCode
 ┃ ┃ ┃ ┃ ┣ 📜GenerateQrCodeRequest.php
 ┃ ┃ ┃ ┃ ┗ 📜RegenerateQrCodeRequest.php
 ┃ ┃ ┃ ┣ 📂Rating
 ┃ ┃ ┃ ┃ ┣ 📜BulkRatingActionRequest.php
 ┃ ┃ ┃ ┃ ┣ 📜ModerateRatingRequest.php
 ┃ ┃ ┃ ┃ ┣ 📜RatingFilterRequest.php
 ┃ ┃ ┃ ┃ ┗ 📜UpdateRatingStatusRequest.php
 ┃ ┃ ┃ ┣ 📂RatingCategory
 ┃ ┃ ┃ ┃ ┣ 📜ReorderCategoryRequest.php
 ┃ ┃ ┃ ┃ ┣ 📜StoreRatingCategoryRequest.php
 ┃ ┃ ┃ ┃ ┗ 📜UpdateRatingCategoryRequest.php
 ┃ ┃ ┃ ┣ 📂Reply
 ┃ ┃ ┃ ┃ ┗ 📜StoreReplyRequest.php
 ┃ ┃ ┃ ┣ 📂Report
 ┃ ┃ ┃ ┃ ┣ 📜BulkReportActionRequest.php
 ┃ ┃ ┃ ┃ ┣ 📜ReplyReportRequest.php
 ┃ ┃ ┃ ┃ ┣ 📜ReportFilterRequest.php
 ┃ ┃ ┃ ┃ ┣ 📜UpdateReportRequest.php
 ┃ ┃ ┃ ┃ ┗ 📜UpdateReportStatusRequest.php
 ┃ ┃ ┃ ┣ 📂ReportCategory
 ┃ ┃ ┃ ┃ ┣ 📜StoreReportCategoryRequest.php
 ┃ ┃ ┃ ┃ ┗ 📜UpdateReportCategoryRequest.php
 ┃ ┃ ┃ ┣ 📂Setting
 ┃ ┃ ┃ ┃ ┗ 📜UpdateSettingRequest.php
 ┃ ┃ ┃ ┣ 📂Unit
 ┃ ┃ ┃ ┃ ┣ 📜StoreUnitRequest.php
 ┃ ┃ ┃ ┃ ┗ 📜UpdateUnitRequest.php
 ┃ ┃ ┃ ┣ 📂UnitDepartment
 ┃ ┃ ┃ ┃ ┣ 📜StoreUnitDepartmentRequest.php
 ┃ ┃ ┃ ┃ ┗ 📜UpdateUnitDepartmentRequest.php
 ┃ ┃ ┃ ┣ 📂UnitPhoto
 ┃ ┃ ┃ ┃ ┣ 📜ReorderPhotoRequest.php
 ┃ ┃ ┃ ┃ ┣ 📜SetPrimaryPhotoRequest.php
 ┃ ┃ ┃ ┃ ┗ 📜UploadUnitPhotoRequest.php
 ┃ ┃ ┃ ┗ 📂UnitType
 ┃ ┃ ┃ ┃ ┣ 📜ReorderUnitTypeRequest.php
 ┃ ┃ ┃ ┃ ┣ 📜StoreUnitTypeRequest.php
 ┃ ┃ ┃ ┃ ┗ 📜UpdateUnitTypeRequest.php
 ┃ ┃ ┣ 📂Employee
 ┃ ┃ ┃ ┣ 📂Profile
 ┃ ┃ ┃ ┃ ┗ 📜UpdateProfileRequest.php
 ┃ ┃ ┃ ┣ 📂Rating
 ┃ ┃ ┃ ┃ ┣ 📜FilterRatingRequest.php
 ┃ ┃ ┃ ┃ ┣ 📜StoreReplyRequest.php
 ┃ ┃ ┃ ┃ ┗ 📜UpdateReplyRequest.php
 ┃ ┃ ┃ ┣ 📂Report
 ┃ ┃ ┃ ┃ ┣ 📜FilterReportRequest.php
 ┃ ┃ ┃ ┃ ┣ 📜StoreReplyRequest.php
 ┃ ┃ ┃ ┃ ┣ 📜UpdateReplyRequest.php
 ┃ ┃ ┃ ┃ ┗ 📜UpdateReportStatusRequest.php
 ┃ ┃ ┃ ┗ 📂Unit
 ┃ ┃ ┃ ┃ ┣ 📜FilterUnitRequest.php
 ┃ ┃ ┃ ┃ ┣ 📜ShowUnitRequest.php
 ┃ ┃ ┃ ┃ ┗ 📜UpdateUnitRequest.php
 ┃ ┃ ┗ 📂Student
 ┃ ┃ ┃ ┣ 📂Auth
 ┃ ┃ ┃ ┃ ┣ 📜StudentLoginRequest.php
 ┃ ┃ ┃ ┃ ┗ 📜StudentRegisterRequest.php
 ┃ ┃ ┃ ┣ 📂Notification
 ┃ ┃ ┃ ┃ ┗ 📜MarkAsReadRequest.php
 ┃ ┃ ┃ ┣ 📂Profile
 ┃ ┃ ┃ ┃ ┣ 📜UpdatePasswordRequest.php
 ┃ ┃ ┃ ┃ ┗ 📜UpdateProfileRequest.php
 ┃ ┃ ┃ ┣ 📂QrCode
 ┃ ┃ ┃ ┃ ┗ 📜QrValidationRequest.php
 ┃ ┃ ┃ ┣ 📂Rating
 ┃ ┃ ┃ ┃ ┣ 📜RatingFilterRequest.php
 ┃ ┃ ┃ ┃ ┣ 📜StoreRatingRequest.php
 ┃ ┃ ┃ ┃ ┗ 📜UpdateRatingRequest.php
 ┃ ┃ ┃ ┗ 📂Report
 ┃ ┃ ┃ ┃ ┣ 📜StoreReportRequest.php
 ┃ ┃ ┃ ┃ ┗ 📜UpdateReportRequest.php
 ┣ 📂Mail
 ┃ ┣ 📂Admin
 ┃ ┃ ┣ 📜AccountDeactivatedMail.php
 ┃ ┃ ┣ 📜ResetPasswordMail.php
 ┃ ┃ ┗ 📜WelcomeMail.php
 ┃ ┣ 📂Employee
 ┃ ┃ ┣ 📜ResetPasswordMail.php
 ┃ ┃ ┗ 📜WelcomeMail.php
 ┃ ┣ 📂Student
 ┃ ┃ ┣ 📜AccountDeactivatedMail.php
 ┃ ┃ ┣ 📜RatingRepliedMail.php
 ┃ ┃ ┣ 📜RatingSubmittedMail.php
 ┃ ┃ ┣ 📜ReportRepliedMail.php
 ┃ ┃ ┣ 📜ReportStatusChangedMail.php
 ┃ ┃ ┣ 📜ReportSubmittedMail.php
 ┃ ┃ ┣ 📜ResetPasswordMail.php
 ┃ ┃ ┣ 📜VerificationMail.php
 ┃ ┃ ┗ 📜WelcomeMail.php
 ┃ ┗ 📂Traits
 ┃ ┃ ┗ 📜HasMailConfiguration.php
 ┣ 📂Models
 ┃ ┣ 📂Authentication
 ┃ ┃ ┣ 📜Admin.php
 ┃ ┃ ┣ 📜Employee.php
 ┃ ┃ ┗ 📜Student.php
 ┃ ┣ 📂Conversation
 ┃ ┃ ┣ 📜Conversation.php
 ┃ ┃ ┣ 📜ConversationParticipant.php
 ┃ ┃ ┣ 📜Message.php
 ┃ ┃ ┣ 📜MessageAttachment.php
 ┃ ┃ ┗ 📜MessageRead.php
 ┃ ┣ 📂Employee
 ┃ ┃ ┣ 📜EmployeePosition.php
 ┃ ┃ ┗ 📜EmployeeUnitAssignment.php
 ┃ ┣ 📂Feedback
 ┃ ┃ ┣ 📜Rating.php
 ┃ ┃ ┣ 📜RatingAttachment.php
 ┃ ┃ ┣ 📜RatingCategory.php
 ┃ ┃ ┣ 📜RatingReply.php
 ┃ ┃ ┣ 📜RatingScore.php
 ┃ ┃ ┗ 📜UnitVisit.php
 ┃ ┣ 📂Report
 ┃ ┃ ┣ 📜Report.php
 ┃ ┃ ┣ 📜ReportAttachment.php
 ┃ ┃ ┣ 📜ReportCategory.php
 ┃ ┃ ┣ 📜ReportReply.php
 ┃ ┃ ┗ 📜ReportStatusHistory.php
 ┃ ┣ 📂System
 ┃ ┃ ┣ 📜Export.php
 ┃ ┃ ┣ 📜ModerationLog.php
 ┃ ┃ ┣ 📜Notification.php
 ┃ ┃ ┗ 📜Setting.php
 ┃ ┗ 📂Unit
 ┃ ┃ ┣ 📜Facility.php
 ┃ ┃ ┣ 📜QrCode.php
 ┃ ┃ ┣ 📜Unit.php
 ┃ ┃ ┣ 📜UnitDepartment.php
 ┃ ┃ ┣ 📜UnitFacility.php
 ┃ ┃ ┣ 📜UnitPhoto.php
 ┃ ┃ ┗ 📜UnitType.php
 ┣ 📂Observers
 ┃ ┣ 📜FacilityObserver.php
 ┃ ┣ 📜RatingObserver.php
 ┃ ┣ 📜ReportObserver.php
 ┃ ┣ 📜UnitDepartmentObserver.php
 ┃ ┣ 📜UnitObserver.php
 ┃ ┗ 📜UnitTypeObserver.php
 ┣ 📂Policies
 ┃ ┣ 📜AdminPolicy.php
 ┃ ┣ 📜ConversationPolicy.php
 ┃ ┣ 📜DashboardPolicy.php
 ┃ ┣ 📜EmployeePolicy.php
 ┃ ┣ 📜ExportPolicy.php
 ┃ ┣ 📜FacilityPolicy.php
 ┃ ┣ 📜ModerationLogPolicy.php
 ┃ ┣ 📜NotificationPolicy.php
 ┃ ┣ 📜QrCodePolicy.php
 ┃ ┣ 📜RatingCategoryPolicy.php
 ┃ ┣ 📜RatingPolicy.php
 ┃ ┣ 📜ReportCategoryPolicy.php
 ┃ ┣ 📜ReportPolicy.php
 ┃ ┣ 📜SettingPolicy.php
 ┃ ┣ 📜StudentPolicy.php
 ┃ ┣ 📜UnitDepartmentPolicy.php
 ┃ ┣ 📜UnitPhotoPolicy.php
 ┃ ┣ 📜UnitPolicy.php
 ┃ ┣ 📜UnitTypePolicy.php
 ┃ ┗ 📜UnitVisitPolicy.php
 ┣ 📂Providers
 ┃ ┣ 📜AppServiceProvider.php
 ┃ ┣ 📜AuthServiceProvider.php
 ┃ ┗ 📜RouteServiceProvider.php
 ┣ 📂Services
 ┃ ┣ 📂Admin
 ┃ ┃ ┣ 📂Conversation
 ┃ ┃ ┃ ┣ 📜ConversationParticipantService.php
 ┃ ┃ ┃ ┣ 📜ConversationService.php
 ┃ ┃ ┃ ┣ 📜MessageAttachmentService.php
 ┃ ┃ ┃ ┣ 📜MessageReadService.php
 ┃ ┃ ┃ ┗ 📜MessageService.php
 ┃ ┃ ┣ 📂Dashboard
 ┃ ┃ ┃ ┗ 📜DashboardService.php
 ┃ ┃ ┣ 📂Employee
 ┃ ┃ ┃ ┗ 📜EmployeeService.php
 ┃ ┃ ┣ 📂Moderation
 ┃ ┃ ┃ ┗ 📜ModerationLogService.php
 ┃ ┃ ┣ 📂Profile
 ┃ ┃ ┃ ┗ 📜ProfileService.php
 ┃ ┃ ┣ 📂QRCode
 ┃ ┃ ┃ ┗ 📜QrCodeService.php
 ┃ ┃ ┣ 📂Rating
 ┃ ┃ ┃ ┣ 📜RatingCategoryService.php
 ┃ ┃ ┃ ┗ 📜RatingManagementService.php
 ┃ ┃ ┣ 📂Report
 ┃ ┃ ┃ ┣ 📜ReportCategoryService.php
 ┃ ┃ ┃ ┗ 📜ReportManagementService.php
 ┃ ┃ ┣ 📂Setting
 ┃ ┃ ┃ ┗ 📜SettingService.php
 ┃ ┃ ┣ 📂Shared
 ┃ ┃ ┃ ┣ 📜BaseAdminService.php
 ┃ ┃ ┃ ┗ 📜IconService.php
 ┃ ┃ ┣ 📂Unit
 ┃ ┃ ┃ ┣ 📜FacilityService.php
 ┃ ┃ ┃ ┣ 📜UnitDepartmentService.php
 ┃ ┃ ┃ ┣ 📜UnitPhotoService.php
 ┃ ┃ ┃ ┣ 📜UnitService.php
 ┃ ┃ ┃ ┗ 📜UnitTypeService.php
 ┃ ┃ ┗ 📂User
 ┃ ┃ ┃ ┗ 📜UserService.php
 ┃ ┣ 📂Employee
 ┃ ┃ ┣ 📂Assignment
 ┃ ┃ ┣ 📂Dashboard
 ┃ ┃ ┣ 📂Profile
 ┃ ┃ ┣ 📂Rating
 ┃ ┃ ┣ 📂Report
 ┃ ┃ ┣ 📂Shared
 ┃ ┃ ┣ 📜AssignedUnitService.php
 ┃ ┃ ┣ 📜BaseEmployeeService.php
 ┃ ┃ ┣ 📜DashboardService.php
 ┃ ┃ ┣ 📜ProfileService.php
 ┃ ┃ ┣ 📜RatingReplyService.php
 ┃ ┃ ┣ 📜ReportReplyService.php
 ┃ ┃ ┗ 📜ReportStatusService.php
 ┃ ┣ 📂Export
 ┃ ┃ ┣ 📂Contracts
 ┃ ┃ ┃ ┗ 📜ExportInterface.php
 ┃ ┃ ┣ 📂Exports
 ┃ ┃ ┃ ┣ 📜BaseExport.php
 ┃ ┃ ┃ ┣ 📜RatingExport.php
 ┃ ┃ ┃ ┗ 📜ReportExport.php
 ┃ ┃ ┣ 📂Manager
 ┃ ┃ ┣ 📂Queue
 ┃ ┃ ┣ 📜CsvExportService.php
 ┃ ┃ ┣ 📜ExcelExportService.php
 ┃ ┃ ┣ 📜ExportManager.php
 ┃ ┃ ┣ 📜ExportQueueService.php
 ┃ ┃ ┣ 📜PdfExportService.php
 ┃ ┃ ┣ 📜ReportGeneratorService.php
 ┃ ┃ ┗ 📜SpreadsheetExport.php
 ┃ ┣ 📂Shared
 ┃ ┃ ┣ 📂File
 ┃ ┃ ┣ 📂GPS
 ┃ ┃ ┣ 📂Notification
 ┃ ┃ ┣ 📂Support
 ┃ ┃ ┣ 📜BaseService.php
 ┃ ┃ ┣ 📜FileUploadService.php
 ┃ ┃ ┣ 📜GpsValidationService.php
 ┃ ┃ ┗ 📜NotificationService.php
 ┃ ┗ 📂Student
 ┃ ┃ ┣ 📂Auth
 ┃ ┃ ┃ ┗ 📜AuthService.php
 ┃ ┃ ┣ 📂Dashboard
 ┃ ┃ ┣ 📂Notification
 ┃ ┃ ┣ 📂Profile
 ┃ ┃ ┣ 📂QRValidation
 ┃ ┃ ┣ 📂Rating
 ┃ ┃ ┣ 📂Report
 ┃ ┃ ┣ 📂Shared
 ┃ ┃ ┣ 📂Unit
 ┃ ┃ ┣ 📜BaseStudentService.php
 ┃ ┃ ┣ 📜DashboardService.php
 ┃ ┃ ┣ 📜NotificationService.php
 ┃ ┃ ┣ 📜ProfileService.php
 ┃ ┃ ┣ 📜QrValidationService.php
 ┃ ┃ ┣ 📜RatingService.php
 ┃ ┃ ┣ 📜ReportService.php
 ┃ ┃ ┗ 📜UnitService.php
 ┗ 📂View
 ┃ ┗ 📂Components
 ┃ ┃ ┣ 📂admin
 ┃ ┃ ┃ ┣ 📂conversations
 ┃ ┃ ┃ ┃ ┣ 📂components
 ┃ ┃ ┃ ┃ ┃ ┗ 📜attachment-card.php
 ┃ ┃ ┃ ┃ ┣ 📜attachment-card.php
 ┃ ┃ ┃ ┃ ┣ 📜conversation-card.php
 ┃ ┃ ┃ ┃ ┣ 📜conversation-status.php
 ┃ ┃ ┃ ┃ ┣ 📜date-divider.php
 ┃ ┃ ┃ ┃ ┣ 📜empty-state.php
 ┃ ┃ ┃ ┃ ┣ 📜loading-skeleton.php
 ┃ ┃ ┃ ┃ ┣ 📜message-status.php
 ┃ ┃ ┃ ┃ ┣ 📜participant-avatar.php
 ┃ ┃ ┃ ┃ ┣ 📜participant-stack.php
 ┃ ┃ ┃ ┃ ┣ 📜typing-indicator.php
 ┃ ┃ ┃ ┃ ┗ 📜unread-badge.php
 ┃ ┃ ┃ ┣ 📂units
 ┃ ┃ ┃ ┃ ┗ 📜trashed.php
 ┃ ┃ ┃ ┣ 📜alert.php
 ┃ ┃ ┃ ┣ 📜Button.php
 ┃ ┃ ┃ ┣ 📜ButtonCreate.php
 ┃ ┃ ┃ ┣ 📜DeleteModalComponent.php
 ┃ ┃ ┃ ┣ 📜EmptyStateComponent.php
 ┃ ┃ ┃ ┣ 📜ExportButtonsComponent.php
 ┃ ┃ ┃ ┣ 📜FilterButtonComponent.php
 ┃ ┃ ┃ ┣ 📜FilterStatusButtonComponent.php
 ┃ ┃ ┃ ┣ 📜GenerateModal.php
 ┃ ┃ ┃ ┣ 📜LogoutModalComponent.php
 ┃ ┃ ┃ ┣ 📜PerPageDropdownComponent.php
 ┃ ┃ ┃ ┣ 📜ReplyModal.php
 ┃ ┃ ┃ ┣ 📜ReplyRatingModal.php
 ┃ ┃ ┃ ┣ 📜RowsPerPageComponent.php
 ┃ ┃ ┃ ┣ 📜SearchButtonComponent.php
 ┃ ┃ ┃ ┣ 📜SortButton.php
 ┃ ┃ ┃ ┣ 📜SortDropdownComponent.php
 ┃ ┃ ┃ ┣ 📜StatusBadge.php
 ┃ ┃ ┃ ┣ 📜StatusFilterDropdownComponent.php
 ┃ ┃ ┃ ┗ 📜UnitTypeIconComponent.php
 ┃ ┃ ┣ 📂modals
 ┃ ┃ ┃ ┗ 📜ReportModal.php
 ┃ ┃ ┣ 📂student
 ┃ ┃ ┃ ┣ 📜AuthModal.php
 ┃ ┃ ┃ ┣ 📜DescriptionComponent.php
 ┃ ┃ ┃ ┣ 📜FacilitiesComponent.php
 ┃ ┃ ┃ ┣ 📜GalleryComponent.php
 ┃ ┃ ┃ ┣ 📜InfoCardComponent.php
 ┃ ┃ ┃ ┣ 📜OperatingHoursComponent.php
 ┃ ┃ ┃ ┣ 📜RatingSummaryComponent.php
 ┃ ┃ ┃ ┗ 📜ReviewListComponent.php
 ┃ ┃ ┗ 📂visitor
 ┃ ┃ ┃ ┣ 📜EmptyStateComponent.php
 ┃ ┃ ┃ ┣ 📜ExportCardComponent.php
 ┃ ┃ ┃ ┣ 📜FacilityItemComponent.php
 ┃ ┃ ┃ ┣ 📜GalleryCardComponent.php
 ┃ ┃ ┃ ┣ 📜GalleryGridComponent.php
 ┃ ┃ ┃ ┣ 📜InfoCardComponent.php
 ┃ ┃ ┃ ┣ 📜NavbarComponent.php
 ┃ ┃ ┃ ┣ 📜OperatingHoursComponent.php
 ┃ ┃ ┃ ┣ 📜ReviewCardComponent.php
 ┃ ┃ ┃ ┣ 📜ReviewListComponent.php
 ┃ ┃ ┃ ┣ 📜StatsCardComponent.php
 ┃ ┃ ┃ ┗ 📜TabNavComponent.php